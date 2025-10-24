<?php

namespace App\Controllers;

use App\Models\Repositories\LoanRepository;
use App\Models\Repositories\BookRepository;
use App\Models\Repositories\MemberRepository;
use App\Models\Entities\Loan;
use DateTime;

/**
 * LoansController - Controlador para gerenciar empréstimos
 */
class LoansController extends BaseController
{
    private LoanRepository $loanRepository;
    private BookRepository $bookRepository;
    private MemberRepository $memberRepository;

    public function __construct()
    {
        $this->loanRepository = new LoanRepository();
        $this->bookRepository = new BookRepository();
        $this->memberRepository = new MemberRepository();
    }

    /**
     * Lista todos os empréstimos
     */
    public function index(): void
    {
        AuthController::requireAuth();

        $loans = $this->loanRepository->findAllWithDetails();

        $this->loadView('loans/index', ['loans' => $loans]);
    }

    /**
     * Exibe formulário de criação de empréstimo
     */
    public function create(): void
    {
        AuthController::requireAuth();

        // Busca membros ativos e livros disponíveis
        $members = $this->memberRepository->findAtivos();
        $books = $this->bookRepository->findDisponiveis();

        $this->loadView('loans/create', [
            'members' => $members,
            'books' => $books
        ]);
    }

    /**
     * Processa criação de novo empréstimo
     */
    public function store(): void
    {
        AuthController::requireAuth();

        if (!$this->isPost()) {
            redirect('loans');
        }

        $errors = [];

        // Obtém dados do formulário
        $memberId = (int)$this->getPost('member_id', 0);
        $bookId = (int)$this->getPost('book_id', 0);
        $dataEmprestimo = $this->getPost('data_emprestimo', '');
        $dataPrevistaDevolucao = $this->getPost('data_prevista_devolucao', '');
        $observacoes = trim($this->getPost('observacoes', ''));

        // Validações
        if ($memberId <= 0) {
            $errors[] = 'Membro é obrigatório';
        } elseif (!$this->memberRepository->exists($memberId)) {
            $errors[] = 'Membro não encontrado';
        }

        if ($bookId <= 0) {
            $errors[] = 'Livro é obrigatório';
        } else {
            $book = $this->bookRepository->findBookById($bookId);
            if (!$book) {
                $errors[] = 'Livro não encontrado';
            } elseif (!$book->isDisponivel()) {
                $errors[] = 'Livro não está disponível para empréstimo';
            }
        }

        if (empty($dataEmprestimo)) {
            $errors[] = 'Data de empréstimo é obrigatória';
        }

        if (empty($dataPrevistaDevolucao)) {
            $errors[] = 'Data prevista de devolução é obrigatória';
        }

        // Se há erros, volta para o formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('loans/create');
        }

        // Cria entidade Loan
        $loan = new Loan(
            $memberId,
            $bookId,
            new DateTime($dataEmprestimo),
            new DateTime($dataPrevistaDevolucao),
            $_SESSION['username'] ?? 'sistema',
            'ativo',
            null,
            $observacoes
        );

        // Inicia transação
        $db = \App\Core\Database::getInstance();
        $db->beginTransaction();

        try {
            // Salva empréstimo
            $loanId = $this->loanRepository->create($loan);

            // Decrementa quantidade disponível do livro
            $this->bookRepository->decrementarDisponivel($bookId);

            $db->commit();

            $_SESSION['success'] = 'Empréstimo registrado com sucesso!';
        } catch (\Exception $e) {
            $db->rollback();
            $_SESSION['errors'] = ['Erro ao registrar empréstimo: ' . $e->getMessage()];
        }

        redirect('loans');
    }

    /**
     * Registra devolução de um empréstimo
     */
    public function devolver(): void
    {
        AuthController::requireAuth();

        $id = (int)$this->getQuery('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('loans');
        }

        $loan = $this->loanRepository->findLoanById($id);

        if (!$loan) {
            $_SESSION['errors'] = ['Empréstimo não encontrado'];
            redirect('loans');
        }

        if ($loan->getStatus() !== 'ativo') {
            $_SESSION['errors'] = ['Este empréstimo já foi devolvido'];
            redirect('loans');
        }

        // Inicia transação
        $db = \App\Core\Database::getInstance();
        $db->beginTransaction();

        try {
            // Registra devolução
            $dataDevolucao = date('Y-m-d');
            $this->loanRepository->registrarDevolucao($id, $dataDevolucao);

            // Incrementa quantidade disponível do livro
            $this->bookRepository->incrementarDisponivel($loan->getBookId());

            $db->commit();

            $_SESSION['success'] = 'Devolução registrada com sucesso!';
        } catch (\Exception $e) {
            $db->rollback();
            $_SESSION['errors'] = ['Erro ao registrar devolução: ' . $e->getMessage()];
        }

        redirect('loans');
    }

    /**
     * Deleta um empréstimo (apenas se não estiver ativo)
     */
    public function delete(): void
    {
        AuthController::requireAuth();

        $id = (int)$this->getQuery('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('loans');
        }

        $loan = $this->loanRepository->findLoanById($id);

        if (!$loan) {
            $_SESSION['errors'] = ['Empréstimo não encontrado'];
            redirect('loans');
        }

        if ($loan->getStatus() === 'ativo') {
            $_SESSION['errors'] = ['Não é possível excluir um empréstimo ativo. Registre a devolução primeiro.'];
            redirect('loans');
        }

        $success = $this->loanRepository->delete($id);

        if ($success) {
            $_SESSION['success'] = 'Empréstimo removido com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao remover empréstimo'];
        }

        redirect('loans');
    }
}
