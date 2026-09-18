<?php

class Personagem {

    private int $id;
    private string $nome;
    private string $classe;
    private int $usuarioId;
    private ?string $caminhoImagem;

    public function __construct(array $dados) {
        $this->id = (int) ($dados['id'] ?? 0);
        $this->nome = $dados['nome'] ?? '';
        $this->classe = $dados['classe'] ?? '';
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
        $this->caminhoImagem = $dados['imagem'] ?? $dados['caminho_imagem'] ?? null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getClasse(): string {
        return $this->classe;
    }

    public function getUsuarioId(): int {
        return $this->usuarioId;
    }

    public function getCaminhoImagem(): ?string {
        return $this->caminhoImagem;
    }

    public function getImagemUrl(): ?string {
        if ($this->caminhoImagem) {
            return '/Trab_Lp3/' . $this->caminhoImagem;
        }

        return null;
    }

    public static function novo(
        string $nome,
        string $classe,
        int $usuarioId,
        ?string $caminhoImagem = null
    ): Personagem {

        if ($usuarioId <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $personagem = new Personagem([
            'usuario_id' => $usuarioId
        ]);

        $personagem->alterarDados(
            $nome,
            $classe,
            $caminhoImagem
        );

        return $personagem;
    }

    public function alterarDados(
        string $nome,
        string $classe,
        ?string $caminhoImagem = null
    ): void {

        $nome = trim($nome);
        $classe = trim($classe);

        if ($nome === '' || $classe === '') {
            throw new InvalidArgumentException(
                'Nome e classe são obrigatórios.'
            );
        }

        $this->nome = $nome;
        $this->classe = $classe;

        if ($caminhoImagem !== null) {
            $this->caminhoImagem = $caminhoImagem;
        }
    }

    public function removerImagem(): void {

        if (
            $this->caminhoImagem &&
            file_exists(__DIR__ . '/../' . $this->caminhoImagem)
        ) {
            unlink(__DIR__ . '/../' . $this->caminhoImagem);
        }

        $this->caminhoImagem = null;
    }

    public function registrarIdGerado(int $id): void {

        if ($id <= 0) {
            throw new InvalidArgumentException('ID inválido.');
        }

        $this->id = $id;
    }
}