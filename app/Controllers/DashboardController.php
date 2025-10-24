<?php

namespace App\Controllers;

use App\Models\Repositories\BookRepository;
use App\Models\Repositories\MemberRepository;
use App\Models\Repositories\LoanRepository;

/**
 * DashboardController - Controlador do painel principal
 */
class DashboardController extends BaseController
{
    private BookRepository $bookRepository;
    private MemberRepository $memberRepository;
    private LoanRepository $loanRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
        $this->memberRepository = new MemberRepository();
        $this->loanRepository = new LoanRepository();
    }

    /**
     * Exibe o dashboard principal
     */
    public function index(): void
    {
        AuthController::requireAuth();

        // Estatísticas
        $totalBooks = $this->bookRepository->count();
        $totalMembers = $this->memberRepository->count();
        $totalLoans = $this->loanRepository->count();
        $activeLoans = count($this->loanRepository->findAtivos());
        $overdueLoans = count($this->loanRepository->findAtrasados());

        // Livros disponíveis
        $availableBooks = count($this->bookRepository->findDisponiveis());

        // Últimos empréstimos
        $recentLoans = array_slice($this->loanRepository->findAllWithDetails(), 0, 5);

        $this->loadView('dashboard/index', [
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'totalLoans' => $totalLoans,
            'activeLoans' => $activeLoans,
            'overdueLoans' => $overdueLoans,
            'availableBooks' => $availableBooks,
            'recentLoans' => $recentLoans
        ]);
    }
}
