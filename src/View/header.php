<?php if (!isset($_SESSION['user_id'])) return; ?>

<header>
    <strong>Motor Mundo</strong>
    <br>

    <nav class="main-menu">
    <ul>
        <li><a href="/MotorMundo/public/index.php">
            <?= I18n::t('dashboard') ?>
        </a></li>

        <?php if (Acl::checkSilent('manage_i18n')): ?>
            <li><a href="/MotorMundo/public/i18n/list.php">
                <?= I18n::t('manage_translations') ?>
            </a></li>
        <?php endif; ?>

        <?php if (Acl::checkSilent('manage_biological_sexes')): ?>
            <li><a href="/MotorMundo/public/biological_sexes/list.php">
                <?= I18n::t('biological_sexes') ?>
            </a></li>
        <?php endif; ?>
        <?php if (Acl::checkSilent('manage_age_groups')): ?>
        <li>
            <a href="/MotorMundo/public/age_groups/list.php">
                <?= I18n::t('age_groups') ?>
            </a>
        </li>
        <?php endif; ?>

        <?php if (Acl::checkSilent('manage_magic_energies')): ?>
        <li>
            <a href="/MotorMundo/public/magic_energies/list.php">
                <?= I18n::t('magic_energies') ?>
            </a>
        </li>
        <?php endif; ?>

        <li class="separator"></li>

        <li>
        <a href="/MotorMundo/public/logout.php">
            <?= I18n::t('logout') ?>
        </a>
        </li>
    </nav>

    <hr>
</header>
