<ul class="nav" id="side-menu">

    <li>
        <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    </li>

    <?php
    if ($cargo === "Administrador") {
        echo("
        <li>
            <a href='#'><i class='fa fa-database fa-fw'></i> Gerir Base de Dados<span class='fa arrow'></span></a>
            <ul class='nav nav-second-level'>
                <li>
                    <a href='gerir-docente-uc.php'><i class='fa fa-chain fa-fw'></i> Docente - UC</a>
                </li>
                <li>
                    <a href='gerir-docentes.php'><i class='fa fa-group fa-fw'></i> Docentes</a>
                </li>
                <li>
                    <a href='gerir-ucs.php'><i class='fa fa-book fa-fw'></i> Unidades Curriculares</a>
                </li>
                <li>
                    <a href='gerir-alunos.php'><i class='fa fa-graduation-cap fa-fw'></i> Alunos</a>
                </li>
                
            </ul>
        </li>
        ");
        echo("
        <li>
            <a href='gerir-administradores.php'><i class='fa fa-gears fa-fw'></i> Gerir Administradores</a>
        </li>
        ");
    }
    ?>

    <li>
        <a href="meus-projetos.php"><i class="fa fa-th-list fa-fw"></i>
            <?php
            if ($cargo === "Administrador" || $cargo === "Docente") {
                echo " Projetos";
            }
            if ($cargo === "Aluno") {
                echo " Meus Projetos";
            }
            ?>
        </a>
    </li>

    <li>
        <a href="novo-projeto.php"><i class="fa fa-file-o fa-fw"></i> Novo Projeto</a>
    </li>

    <li>
        <a href="#"><i class="fa fa-gear fa-fw"></i> Editar Conta<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li>
                <a href="alterar-password.php"><i class="fa fa-key fa-fw"></i> Alterar Palavra
                    Passe</a>
            </li>
            <?php
            if ($cargo === "Administrador") {
            } else {
                echo("
                <li>
                    <a href='dados-pessoais.php'><i class='fa fa-edit fa-fw'></i> Alterar Dados
                        Pessoais</a>
                </li>
                ");
            }
            ?>
        </ul>
        <!-- /.nav-second-level -->
    </li>

</ul>