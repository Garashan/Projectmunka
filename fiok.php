<style>
        .login_items {
            margin-inline: auto;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: self-start;
        }

        .login {
			align-items: center;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }

        .login h2 {
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
        }

        .login label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .login input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .login button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background-color: #2d7ff9;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login button:hover {
            background-color: #1f65c8;
        }
    </style>
<?php
require_once __DIR__ . '/include/auth.php';

$hiba = '';

if (!isLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $hiba = 'Hiba: Adja meg a felhasználónevét!';
    }
    elseif ($password === '') {
        $hiba = 'Hiba: Adja meg a jelszavát!';
    }
    else {

        $stmt = $connect->prepare(
            "SELECT id, felhasznalo_nev, jelszo
             FROM projectmunka_users
             WHERE felhasznalo_nev = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        if ($user && password_verify($password, $user['jelszo'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['felhasznalo_nev'];

            header('Location: /fiok');
            exit;

        } else {
            $hiba = 'Hibás felhasználónév vagy jelszó!';
        }
    }
}
?>

<?php if (!isLoggedIn()): ?>

<div class="login_items">
    <div class="login">

        <h2>Kérlek jelentkezz be!</h2>

        <form action="/fiok" method="post">

            <div>
                <label for="username">Felhasználónév</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    autocomplete="username"
                    required
                >
            </div>

            <div>
                <label for="password">Jelszó</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div>
                <button type="submit" name="submit">
                    Bejelentkezés
                </button>
            </div>

            <?php if ($hiba !== ''): ?>
                <div class="hiba">
                    <?= htmlspecialchars($hiba, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

        </form>

    </div>
</div>

<?php else: ?>

    <h2>
        Szia,
        <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>!
    </h2>

<?php endif; ?>