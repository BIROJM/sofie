<?php
include('./class/auth.class.php');
$status = Auth::getAuth('soc_kouakou', 'azerty');
$_SESSION['isLogin'] = $status;

?>

<div class="container-fluid">
        <div id="page-login" class="row">
            <div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                <div class="box">
                    <div class="box-content">
                        <div class="text-center">
                            <h3 class="page-header">
                                Authentification
                            </h3>
                        </div>
                      <?php if(!$_SESSION['isLogin']) {?>
                            <div class="row text-center">
                                <span class="text-danger">
                                    Impossible de se connecter
                                </span>
                            </div>
                        <?php }?>
                        <form method="post">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon"><label for="username" class="fa fa-user"></label></div>
                                    <input type="text" class="form-control" id="username" name="_username" value="" placeholder="Nom d'utlisateur" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon"><label for="password" class="fa fa-key"></label></div>
                                    <input type="password" class="form-control" id="password" name="_password" placeholder="Mot de passe" />
                                </div>
                            </div>
                            <div>
                                <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}" />
                                <input type="hidden" name="_s2_auth_action" value="1" />
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary form-control">Connexion</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>