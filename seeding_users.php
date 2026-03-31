<?php
/**
 * Seeder Script for SOFIE Users
 * Compatible with Symfony 2.7 SHA-256 Encoding
 */

$host = 'db';
$db   = 'db_sofiev4';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function encodePassword($password, $salt) {
    // Symfony 2 SHA-256 base64 implementation: base64_encode(hash('sha256', password . '{' . salt . '}', true))
    $salted = $password . '{' . $salt . '}';
    $digest = hash('sha256', $salted, true);
    return base64_encode($digest);
}

$defaultPassword = 'sofie2026';
$salt = 'sofie_salt_2026'; // Constant salt for simplicity in this seeder

$usersToCreate = [
    [
        'username' => 'admin_test',
        'roles' => 'a:2:{i:0;s:9:"ROLE_USER";i:1;s:10:"ROLE_ADMIN";}',
        'qualification' => 140, // Administrateur
    ],
    [
        'username' => 'comite_test',
        'roles' => 'a:1:{i:0;s:9:"ROLE_USER";}',
        'qualification' => 1, // Comite Eau
    ],
    [
        'username' => 'reparateur_test',
        'roles' => 'a:1:{i:0;s:9:"ROLE_USER";}',
        'qualification' => 2, // Reparateur
    ],
    [
        'username' => 'formen_test',
        'roles' => 'a:1:{i:0;s:9:"ROLE_USER";}',
        'qualification' => 3, // Agent Formen
    ],
    [
        'username' => 'sociologue_test',
        'roles' => 'a:1:{i:0;s:9:"ROLE_USER";}',
        'qualification' => 4, // Sociologue
    ],
    [
        'username' => 'dr_test',
        'roles' => 'a:1:{i:0;s:9:"ROLE_USER";}',
        'qualification' => 5, // Directeur Regional
    ],
    [
        'username' => '0101010101', // User requested by the user
        'roles' => 'a:2:{i:0;s:9:"ROLE_USER";i:1;s:10:"ROLE_ADMIN";}',
        'qualification' => 140,
    ]
];

echo "Démarrage du seeding...\n";

foreach ($usersToCreate as $u) {
    // 1. Create Agent first (if needed for agent_id)
    $stmt = $pdo->prepare("INSERT INTO t_agent (NomAgent, Qualification, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([strtoupper($u['username']), $u['qualification']]);
    $agentId = $pdo->lastInsertId();

    // 2. Create User
    $encodedPass = encodePassword($defaultPassword, $salt);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, salt, roles, is_active, agent_id, created_at, updated_at) VALUES (?, ?, ?, ?, 1, ?, NOW(), NOW())");
    try {
        $stmt->execute([$u['username'], $encodedPass, $salt, $u['roles'], $agentId]);
        echo "Utilisateur créé : {$u['username']} (Agent ID: $agentId)\n";
    } catch (Exception $e) {
        echo "Erreur lors de la création de {$u['username']} : " . $e->getMessage() . "\n";
    }
}

echo "Seeding terminé !\n";
