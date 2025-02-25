<?php include_once __DIR__ . '/../layout/header.php'; ?>

<main class="content">
    <h2>Gestion des Utilisateurs</h2>

    <?php if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin'): ?>
        <p>Accès refusé</p>
    <?php else: ?>

        <div class="dashboard-actions">
            <ul>
                <li><a href="#recherche" class="btn-login">Rechercher un Utilisateur</a></li>
                <li><a href="#creer" class="btn-login">Créer un Utilisateur</a></li>
                <li><a href="#modifier" class="btn-login">Modifier un Utilisateur</a></li>
                <li><a href="#supprimer" class="btn-login">Supprimer un Utilisateur</a></li>
                <li><a href="#statistiques" class="btn-login">Statistiques</a></li>
            </ul>
        </div>

        <!-- Recherche d'utilisateur -->
        <div id="recherche" style="margin-top: 40px;">
            <h3>Rechercher un Utilisateur</h3>
            <form action="<?= BASE_URL ?>index.php?controller=gestionutilisateurs&action=search" method="POST">
                <label for="search-user">Nom, Prénom ou Email :</label>
                <input type="text" id="search-user" name="search_query" required>
                <button type="submit" class="btn">Rechercher</button>
            </form>
        </div>

        <hr>

        <!-- Résultat de la recherche -->
        <?php if (isset($search_result) && !empty($search_result)): ?>
            <div id="resultat">
                <h3>Résultat de la recherche :</h3>
                <p><strong>Nom :</strong> <?= htmlspecialchars($search_result['nom']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($search_result['prenom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($search_result['email']) ?></p>
                <p><strong>Rôle :</strong> <?= htmlspecialchars($search_result['role']) ?></p>

                <h3>Modifier cet utilisateur</h3>
                <form action="<?= BASE_URL ?>index.php?controller=gestionutilisateurs&action=update" method="POST">
                    <input type="hidden" name="id" value="<?= $search_result['id'] ?>">

                    <label>Nouveau Nom :</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($search_result['nom']) ?>">

                    <label>Nouveau Prénom :</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($search_result['prenom']) ?>">

                    <label>Nouvel Email :</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($search_result['email']) ?>">

                    <label>Nouveau Rôle :</label>
                    <select name="role">
                        <option value="Etudiant" <?= $search_result['role'] == 'Etudiant' ? 'selected' : '' ?>>Étudiant</option>
                        <option value="pilote" <?= $search_result['role'] == 'pilote' ? 'selected' : '' ?>>Pilote</option>
                        <option value="Admin" <?= $search_result['role'] == 'Admin' ? 'selected' : '' ?>>Administrateur</option>
                    </select>

                    <button type="submit" class="btn">Modifier</button>
                </form>

                <h3>Supprimer cet utilisateur</h3>
                <form action="<?= BASE_URL ?>index.php?controller=gestionutilisateurs&action=delete" method="POST">
                    <input type="hidden" name="id" value="<?= $search_result['id'] ?>">
                    <button type="submit" class="btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</button>
                </form>
            </div>
        <?php elseif (isset($search_result) && empty($search_result)): ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php endif; ?>

        <hr>

        <!-- Créer un utilisateur -->
        <div id="creer" style="margin-top: 40px;">
            <h3>Créer un Utilisateur</h3>
            <form action="<?= BASE_URL ?>index.php?controller=gestionutilisateurs&action=create" method="POST">
                <label for="nom-create-user">Nom :</label>
                <input type="text" id="nom-create-user" name="nom" required>

                <label for="prenom-create-user">Prénom :</label>
                <input type="text" id="prenom-create-user" name="prenom" required>

                <label for="email-create-user">Email :</label>
                <input type="email" id="email-create-user" name="email" required>

                <label for="role-create-user">Rôle :</label>
                <select id="role-create-user" name="role" required>
                    <option value="Etudiant">Étudiant</option>
                    <option value="pilote">Pilote</option>
                    <option value="Admin">Administrateur</option>
                </select>

                <label for="password-create-user">Mot de passe :</label>
                <input type="password" id="password-create-user" name="password" required>

                <button type="submit" class="btn">Créer</button>
            </form>
        </div>

        <!-- Statistiques -->
        <div id="statistiques" style="margin-top: 40px;">
            <h3>Statistiques</h3>
            <p>Nombre total d'utilisateurs : <?= $stats['total_users'] ?? 'N/A' ?></p>
            <p>Nombre d'étudiants : <?= $stats['total_etudiants'] ?? 'N/A' ?></p>
            <p>Nombre de pilotes : <?= $stats['total_pilotes'] ?? 'N/A' ?></p>
            <p>Nombre d'administrateurs : <?= $stats['total_admins'] ?? 'N/A' ?></p>
        </div>

    <?php endif; ?>

</main>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
