<?php
function pais()
{
    echo '
    <!DOCTYPE html>
    <html lang="et">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Sookla menuu</title>
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
                  crossorigin="anonymous">
        </head>
        <body>
    ';
}

function jalus(){
    echo '
    	<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
    ';
}

function soodus($hind, $soodusProtsent){
    return round($hind * ((100 - $soodusProtsent) / 100), 2);
}

function menu_element($liik, $andmed){
    echo '<div class="card m-3">
        <div class="card-header alert-dark">
            <a href="#'.$liik.'" data-parent="#accordion" data-toggle="collapse" >
                <h2 class="text-dark">'.strtoupper($liik).' <i class="fas fa-utensil-spoon"></i></h2>
            </a>
        </div>';
    echo '<div id="'.$liik.'" class="collapse">';
    foreach ($andmed as $element=>$info){
        echo '<ul class="list-group">';
        echo '<li class="list-group-item">';
        echo '<p class="mb-0">'.$info['nimetus'].' <br>';
        echo '<span class="small text-secondary">'.$info['kirjeldus'].'</span><br>';
        echo '<span class="badge badge-info">'.$info['hind'].'&euro;</span>';
        echo '<span class="badge badge-success">'.soodus($info['hind'], 15).'&euro;</span>';
        echo '</p>
                </li>';
        echo '</ul>';
    }
    echo '</div>';
    echo '</div>';
}

function menu($menu){
    echo '<div class="container-fluid text-center">
            <div class="row">
                <div class="col">
                    <div id="accordion">';
    foreach ($menu as $liik => $sisu){
        menu_element($liik, $sisu);
    }
    echo '            </div>
                 </div>
            </div>
        </div>';
}
