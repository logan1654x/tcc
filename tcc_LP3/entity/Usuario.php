<?php

class Usuario {

    private int    $id;
    private string $nome;
    private string $email;      
    private string $senha;
    private string $criadoEm;
    private ?string $fotoPerfil;
    private ?string $biografia;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->nome      = $dados['nome']       ?? '';
        $this->email     = $dados['email']      ?? '';  
        $this->senha     = $dados['senha']      ?? '';
        $this->criadoEm  = $dados['criado_em']  ?? '';
        $this->fotoPerfil = $dados['foto_perfil'] ?? null;
        $this->biografia  = $dados['biografia'] ?? null;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getEmail(): string { return $this->email; }  
    public function getSenha(): string { return $this->senha; }
    public function getCriadoEm(): string { return $this->criadoEm; }
    public function getFotoPerfil(): ?string { return $this->fotoPerfil; }
    public function getBiografia(): ?string { return $this->biografia; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setSenha(string $senha): void { $this->senha = $senha; }
    public function setFotoPerfil(?string $foto): void { $this->fotoPerfil = $foto; }
    public function setBiografia(?string $bio): void { $this->biografia = $bio; }

    /**
     * Factory method para criar um novo usuário (sem ID)
     */
    public static function novo(string $nome, string $email, string $senhaHash): self {
        $usuario = new self([]);
        $usuario->nome  = $nome;
        $usuario->email = $email;
        $usuario->senha = $senhaHash;
        return $usuario;
    }

    /**
     * Altera os dados do usuário
     */
    public function alterarDados(string $nome, string $email, string $senhaHash): void {
        $this->nome  = $nome;
        $this->email = $email;
        $this->senha = $senhaHash;
    }
}