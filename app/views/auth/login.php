<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script>
        window.activeAuthView = "<?= $_SESSION['active_view'] ?? 'view-login' ?>";
        <?php unset($_SESSION['active_view']); ?>
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green - Autenticação Institucional</title>
    <meta name="description" content="Portal de acesso institucional com gestão avançada de permissões e segurança.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= URL_ROOT ?>/styles.css">
    
    <!-- Icons (Ionicons) -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

    <!-- Background Elements for Premium Feel -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Main Container -->
    <main class="auth-container">
        <div class="auth-box glass-panel" style="position: relative;">
            
            <!-- Voltar ao Site -->
            <a href="<?= URL_ROOT ?>/" style="position: absolute; top: 25px; left: 25px; text-decoration: none; color: #64748b; display: flex; align-items: center; gap: 5px; font-size: 0.95rem; font-weight: 600; transition: color 0.3s;" onmouseover="this.style.color='#10b981'" onmouseout="this.style.color='#64748b'">
                <ion-icon name="arrow-back-outline"></ion-icon> Voltar ao Site
            </a>

            <!-- Branding -->
            <div class="brand" style="margin-bottom: 1.5rem;">
                <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Green Hard & Softh" style="max-width: 180px; height: auto; display: block; margin: 0 auto;">
            </div>

            <!-- Login View -->
            <form id="view-login" class="auth-view active" action="<?= URL_ROOT ?>/auth/login" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                
                <?php if(isset($_SESSION['flash_error'])): ?>
                    <p style="color:var(--super-color); margin-bottom:1rem; text-align:center;">
                        <ion-icon name="warning"></ion-icon> <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                    </p>
                <?php endif; ?>
                <?php if(isset($_SESSION['flash_success'])): ?>
                    <p style="color:#10b981; margin-bottom:1rem; text-align:center;">
                        <ion-icon name="checkmark-circle"></ion-icon> <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
                    </p>
                <?php endif; ?>
                <div class="view-header" style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.5rem; color: var(--primary-hex); font-weight: 700;">Portal Institucional</h2>
                    <p class="text-muted small">Introduza as suas credenciais para aceder ao sistema.</p>
                </div>

                <div class="input-group">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" id="login-email" name="email" class="input-field" placeholder=" " required>
                    <label for="login-email">Email institucional</label>
                </div>

                <div class="input-group">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" id="login-password" name="password" class="input-field" placeholder=" " required>
                    <label for="login-password">Senha</label>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox">
                        <span class="checkmark"></span>
                        Manter conectado
                    </label>
                    <a href="#" class="text-link" onclick="switchView('view-forgot')">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <span>Acessar</span>
                    <ion-icon name="log-in-outline"></ion-icon>
                </button>

                <div class="form-footer">
                    <p>Não possui a conta? <a href="#" class="text-link font-semibold" onclick="switchView('view-register')">Cadastre-se </a></p>
                </div>
            </form>

            <!-- Register View -->
            <form id="view-register" class="auth-view" action="<?= URL_ROOT ?>/auth/register" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                
                <div class="view-header">
                    <h2>Criar Conta</h2>
                </div>

                <div class="input-group">
                    <ion-icon name="person-outline"></ion-icon>
                    <input type="text" name="nome" id="reg-name" class="input-field" placeholder=" " required>
                    <label for="reg-name">Nome completo</label>
                </div>

                <div class="input-group">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" name="email" id="reg-email" class="input-field" placeholder=" " required>
                    <label for="reg-email">Email (ex: ghsespf@hotmail.com)</label>
                </div>

                <input type="hidden" name="tipo" value="estudante">


                <div class="input-group">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password" id="reg-password" class="input-field" placeholder=" " required>
                    <label for="reg-password">Senha forte</label>
                </div>

                <div class="password-strength">
                    <div class="strength-bar"><div id="strength-fill" class="fill"></div></div>
                    <span id="strength-text" class="text-hint">A senha deve ter 8+ caracteres, números e símbolos.</span>
                </div>


                <button type="submit" class="btn btn-primary btn-block">
                    <span>Criar Conta</span>
                    <ion-icon name="person-add-outline"></ion-icon>
                </button>

                <div class="form-footer">
                    <p>Já possui conta? <a href="#" class="text-link font-semibold" onclick="switchView('view-login')">Fazer Login</a></p>
                </div>
            </form>

            <!-- Forgot Password View -->
            <form id="view-forgot" class="auth-view" action="<?= URL_ROOT ?>/auth/forgot" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                
                <a href="#" class="back-link" onclick="switchView('view-login')">
                    <ion-icon name="arrow-back-outline"></ion-icon> Voltar
                </a>
                <div class="view-header">
                    <h2>Recuperar Senha</h2>
                </div>

                <div class="input-group">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" id="forgot-email" name="email" class="input-field" placeholder=" " required>
                    <label for="forgot-email">Email institucional</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <span>Enviar Link de Recuperação</span>
                    <ion-icon name="paper-plane-outline"></ion-icon>
                </button>
            </form>

            <!-- Two-Factor Authentication (2FA) View -->
            <form id="view-2fa" class="auth-view" action="<?= URL_ROOT ?>/auth/verify2fa" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                
                <div class="view-header">
                    <h2>Verificação 2FA</h2>
                </div>

                <div class="code-inputs" style="display:flex; justify-content:center;">
                    <input type="text" name="code" maxlength="6" class="input-field" style="text-align:center; font-size:1.5rem; letter-spacing:0.5rem;" placeholder="000000" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">
                    <span>Verificar Código</span>
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </button>
                
                <div class="form-footer mt-4">
                    <p>Não recebeu? <a href="#" class="text-link font-semibold">Reenviar código</a></p> <br>
                    <a href="#" class="text-link" onclick="switchView('view-login')">Cancelar</a>
                </div>
            </form>

        </div>


    </main>

    <!-- Notification Toast -->
    <div id="toast" class="toast"></div>

    <script>
        function switchView(viewId) {
            document.querySelectorAll('.auth-view').forEach(v => {
                v.classList.remove('active');
                v.style.display = 'none';
            });
            const activeView = document.getElementById(viewId);
            activeView.style.display = 'block';
            setTimeout(() => activeView.classList.add('active'), 10);
        }
    </script>
    <script src="<?= URL_ROOT ?>/js/script.js"></script>
</body>
</html>
