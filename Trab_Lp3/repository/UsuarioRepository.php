<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Usuario.php';

class UsuarioRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorEmail(string $email): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Usuario($dados);
        }

        return null;
    }
    
    public function buscarPorId(int $id): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Usuario($dados);
        }

        return null;
    }
      
    public function inserir(string $nome, string $email, string $senhaHash): void {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM usuario WHERE email = :email');
        $stmt->execute([':email' => $email]);
        
        if ($stmt->fetchColumn() > 0) {
            throw new RuntimeException('E-mail já cadastrado.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO usuario (nome, email, senha, criado_em) VALUES (:nome, :email, :senha, NOW())'
        );
        $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':senha' => $senhaHash
        ]);
    }

    /**
     * Atualiza os dados do perfil do usuário (biografia e foto)
     */
    public function atualizarPerfil(Usuario $usuario): void {
        $stmt = $this->pdo->prepare(
            'UPDATE usuario SET biografia = :biografia, foto_perfil = :foto_perfil WHERE id = :id'
        );
        $stmt->execute([
            ':biografia'   => $usuario->getBiografia(),
            ':foto_perfil' => $usuario->getFotoPerfil(),
            ':id'          => $usuario->getId()
        ]);
    }

    /**
     * Atualiza dados básicos do usuário (nome e email)
     */
    public function atualizarDadosBasicos(int $id, string $nome, string $email): void {
        $stmt = $this->pdo->prepare(
            'UPDATE usuario SET nome = :nome, email = :email WHERE id = :id'
        );
        $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':id'    => $id
        ]);
    }

    /**
     * Atualiza a senha do usuário
     */
    public function atualizarSenha(int $id, string $novaSenhaHash): void {
        $stmt = $this->pdo->prepare(
            'UPDATE usuario SET senha = :senha WHERE id = :id'
        );
        $stmt->execute([
            ':senha' => $novaSenhaHash,
            ':id'    => $id
        ]);
    }

    /**
     * Atualiza apenas a foto de perfil
     */
    public function atualizarFotoPerfil(int $userId, ?string $caminhoFoto): void {
        $stmt = $this->pdo->prepare(
            'UPDATE usuario SET foto_perfil = :foto_perfil WHERE id = :id'
        );
        $stmt->execute([
            ':foto_perfil' => $caminhoFoto,
            ':id'          => $userId
        ]);
    }

    /**
     * Atualiza apenas a biografia
     */
    public function atualizarBiografia(int $userId, ?string $biografia): void {
        $stmt = $this->pdo->prepare(
            'UPDATE usuario SET biografia = :biografia WHERE id = :id'
        );
        $stmt->execute([
            ':biografia' => $biografia,
            ':id'        => $userId
        ]);
    }

    /**
     * Busca todos os usuários
     */
    public function listarTodos(): array {
        $stmt = $this->pdo->query('SELECT * FROM usuario ORDER BY nome ASC');
        $usuarios = [];
        foreach ($stmt->fetchAll() as $dados) {
            $usuarios[] = new Usuario($dados);
        }
        return $usuarios;
    }

    /**
     * Conta quantos personagens um usuário tem
     */
    public function contarPersonagens(int $usuarioId): int {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM personagem WHERE usuario_id = :uid');
        $stmt->execute([':uid' => $usuarioId]);
        return (int) $stmt->fetchColumn();
    }
} 