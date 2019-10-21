<nav class="navbar navbar-expand-md navbar-dark bg-dark "  id='navmobile'>
    <div class="container">
        <a class="navbar-brand" href="index.php">SALLEA LOCATION</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarsExampleDefault">
            <ul class="navbar-nav m-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= URL.'index.php' ?>">Accueil</a>
                </li>
                <?php
                if(!isconnected()){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL.'index.php' ?>" data-toggle="modal" data-target="#exampleModal" id="connexion">connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL.'index.php' ?>" data-toggle="modal" data-target=".bd-example-modal-lg" id="trigger-fiche">inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>   
                <?php } else{ ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL.'profil.php' ?>">Compte<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL.'contact.php' ?>">Contact</a>
                    </li>   
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL.'index.php?action=deconnexion' ?>">Deconnexion</a>
                    </li>
                <?php }?>
                <?php 
                if( isAdmin()){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL .'admin/index.php' ?>">Statistiques</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuadmin" role="button" data-toggle="dropdown"><i class="fas fa-tools"></i> Admin</a>
                        <div class="dropdown-menu" aria-labelledby="menuadmin">
                            <a class="dropdown-item" href="<?= URL . 'admin/gestion_produits.php?page=1' ?>">Gestion des produits</a>
                            <a class="dropdown-item" href="<?= URL . 'admin/gestion_membres.php?page=1' ?>">Gestion des membres</a>
                            <a class="dropdown-item" href="<?= URL . 'admin/gestion_commandes.php?page=1' ?>">Gestion des commandes</a>
                            <a class="dropdown-item" href="<?= URL . 'admin/gestion_salles.php?page=1' ?>">Gestion des salles</a>
                            <a class="dropdown-item" href="<?= URL . 'admin/gestion_avis.php?page=1' ?>">Gestion des avis</a>
                        </div>                    
                    </li>
                    <?php 
                } 
                ?>  
            </ul>
            <form class="form-inline my-2 my-lg-0">
                
                <a class="btn btn-success btn-sm ml-3" href="panier.php">
                    <i class="fa fa-shopping-cart"></i> panier
                    <span class="badge badge-light"><?php nbArticles() ?></span>
                </a>
            </form>
        </div>
    </div>
</nav>