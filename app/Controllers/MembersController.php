<?php

namespace App\Controllers;

use App\Models\Repositories\MemberRepository;
use App\Models\Entities\Member;
use DateTime;

/**
 * MembersController - Controlador para gerenciar membros
 */
class MembersController extends BaseController
{
    private MemberRepository $memberRepository;

    public function __construct()
    {
        $this->memberRepository = new MemberRepository();
    }

    /**
     * Lista todos os membros
     */
    public function index(): void
    {
        AuthController::requireAuth();

        $members = $this->memberRepository->getAllMembers();

        $this->loadView('members/index', ['members' => $members]);
    }

    /**
     * Exibe formulário de criação de membro
     */
    public function create(): void
    {
        AuthController::requireAuth();

        $this->loadView('members/create');
    }

    /**
     * Processa criação de novo membro
     */
    public function store(): void
    {
        AuthController::requireAuth();

        if (!$this->isPost()) {
            redirect('members');
        }

        $errors = [];

        // Obtém dados do formulário
        $nome = trim($this->getPost('nome', ''));
        $email = trim($this->getPost('email', ''));
        $telefone = trim($this->getPost('telefone', ''));
        $endereco = trim($this->getPost('endereco', ''));
        $cpf = trim($this->getPost('cpf', ''));
        $dataNascimento = $this->getPost('data_nascimento', '');
        $categoria = $this->getPost('categoria', '');

        // Validações
        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório';
        }
        if (empty($email)) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        } elseif ($this->memberRepository->emailExists($email)) {
            $errors[] = 'Email já cadastrado';
        }
        if (empty($telefone)) {
            $errors[] = 'Telefone é obrigatório';
        }
        if (empty($endereco)) {
            $errors[] = 'Endereço é obrigatório';
        }
        if (empty($cpf)) {
            $errors[] = 'CPF é obrigatório';
        } elseif ($this->memberRepository->cpfExists($cpf)) {
            $errors[] = 'CPF já cadastrado';
        }
        if (empty($dataNascimento)) {
            $errors[] = 'Data de nascimento é obrigatória';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }

        // Se há erros, volta para o formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('members/create');
        }

        // Cria entidade Member
        $member = new Member(
            $nome,
            $email,
            $telefone,
            $endereco,
            $cpf,
            new DateTime($dataNascimento),
            $categoria,
            true
        );

        // Salva no banco
        $memberId = $this->memberRepository->create($member);

        if ($memberId) {
            $_SESSION['success'] = 'Membro cadastrado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao cadastrar membro'];
        }

        redirect('members');
    }

    /**
     * Exibe formulário de edição de membro
     */
    public function edit(): void
    {
        AuthController::requireAuth();

        $id = (int)$this->getQuery('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('members');
        }

        $member = $this->memberRepository->findMemberById($id);

        if (!$member) {
            $_SESSION['errors'] = ['Membro não encontrado'];
            redirect('members');
        }

        $this->loadView('members/edit', ['member' => $member]);
    }

    /**
     * Processa atualização de membro
     */
    public function update(): void
    {
        AuthController::requireAuth();

        if (!$this->isPost()) {
            redirect('members');
        }

        $id = (int)$this->getPost('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('members');
        }

        $errors = [];

        // Obtém dados do formulário
        $nome = trim($this->getPost('nome', ''));
        $email = trim($this->getPost('email', ''));
        $telefone = trim($this->getPost('telefone', ''));
        $endereco = trim($this->getPost('endereco', ''));
        $cpf = trim($this->getPost('cpf', ''));
        $dataNascimento = $this->getPost('data_nascimento', '');
        $categoria = $this->getPost('categoria', '');

        // Validações
        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório';
        }
        if (empty($email)) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        } elseif ($this->memberRepository->emailExists($email, $id)) {
            $errors[] = 'Email já cadastrado para outro membro';
        }
        if (empty($telefone)) {
            $errors[] = 'Telefone é obrigatório';
        }
        if (empty($endereco)) {
            $errors[] = 'Endereço é obrigatório';
        }
        if (empty($cpf)) {
            $errors[] = 'CPF é obrigatório';
        } elseif ($this->memberRepository->cpfExists($cpf, $id)) {
            $errors[] = 'CPF já cadastrado para outro membro';
        }
        if (empty($dataNascimento)) {
            $errors[] = 'Data de nascimento é obrigatória';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }

        // Se há erros, volta para o formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('members/edit?id=' . $id);
        }

        // Busca membro atual
        $currentMember = $this->memberRepository->findMemberById($id);

        if (!$currentMember) {
            $_SESSION['errors'] = ['Membro não encontrado'];
            redirect('members');
        }

        // Atualiza entidade
        $currentMember->setNome($nome);
        $currentMember->setEmail($email);
        $currentMember->setTelefone($telefone);
        $currentMember->setEndereco($endereco);
        $currentMember->setCpf($cpf);
        $currentMember->setDataNascimento(new DateTime($dataNascimento));
        $currentMember->setCategoria($categoria);

        // Salva no banco
        $success = $this->memberRepository->update($currentMember);

        if ($success) {
            $_SESSION['success'] = 'Membro atualizado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao atualizar membro'];
        }

        redirect('members');
    }

    /**
     * Deleta um membro
     */
    public function delete(): void
    {
        AuthController::requireAuth();

        $id = (int)$this->getQuery('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('members');
        }

        $member = $this->memberRepository->findMemberById($id);

        if (!$member) {
            $_SESSION['errors'] = ['Membro não encontrado'];
            redirect('members');
        }

        $success = $this->memberRepository->delete($id);

        if ($success) {
            $_SESSION['success'] = 'Membro removido com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao remover membro'];
        }

        redirect('members');
    }
}
