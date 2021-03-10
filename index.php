<?php
session_start();

if ( !isset($_SESSION['user']) ) {
    $_SESSION['user'] = [
        'id' => 1
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Web Scrapper</title>

    <!-- Bootstrap Core CSS -->
    <link href="/templates/startadmin/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/templates/startadmin/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="/templates/startadmin/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/templates/startadmin/css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="/templates/startadmin/css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/templates/startadmin/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/dev/webscrapper/view/css/style.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Pablo Ripoll</a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="/"><i class="fa fa-home fa-fw"></i> PR Website</a></li>
        </ul>

        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">
            <li class="dropdown navbar-inverse">
                <!-- <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell fa-fw"></i> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    <li>
                        <a href="#">
                            <div>
                                <i class="fa fa-comment fa-fw"></i> New Comment
                                <span class="pull-right text-muted small">4 minutes ago</span>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="text-center" href="#">
                            <strong>See All Alerts</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul> -->
            </li>
            <!-- <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> Usuario <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="#"><i class="fa fa-user fa-fw"></i> Mi Cuenta</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="exit.php"><i class="fa fa-sign-out fa-fw"></i> Salir</a>
                    </li>
                </ul>
            </li> -->
        </ul>

        <!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">

                <ul class="nav" id="side-menu">                    
                    <li>
                        <a href="/dev/webscrapper/" class="active">
                            <i class="fa fa-search fa-fw"></i> Web Scrapper
                        </a>
                    </li>
                    <!-- <li>
                        <a href="#">
                            <i class="fa fa-dashboard fa-fw"></i> Historial
                        </a>
                    </li> -->
                    <!-- <li>
                        <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Second Level Item</a>
                            </li>
                            <li>
                                <a href="#">Third Level <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Third Level Item</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li> -->
                </ul>

            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Web Scrapper</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-sm-12">
                    <div class="input-group custom-search-form">
                        <input id="searcher_input" type="text" class="form-control" placeholder="Buscar palabras..." autofocus>
                        <span class="input-group-btn">
                            <button id="searcher_btn" class="btn btn-primary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>

                    <div class="panel panel-default" style="margin-top:20px">
                        <div class="panel-heading">
                            Estadística de búsqueda: <span class="search-results-for"></span>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Dominios encontrados</th>
                                            <th class="text-center">suma de búsqueda</th>
                                            <th class="text-center">suma histórica</th>
                                        </tr>
                                    </thead>
                                    <tbody id="search-result-statistics">
                                        <tr class="odd gradeX">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                    </div>

                    <div class="panel panel-default" style="margin-top:20px">
                        <div class="panel-heading">
                            Resultado de búsqueda: <span class="search-results-for"></span>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <tbody id="search-result-items">                                        

                                        <!-- <tr class="odd gradeX">
                                            <td>
                                                <a><h5>No hay resultados de búsqueda</h5></a>
                                            </td>                                            
                                        </tr> -->

                                    </tbody>
                                </table>
                            </div>
                            
                        </div><!-- /.panel-body -->
                        
                        <div class="panel-footer">
                            
                            <ul class="pagination">
                                
                            </ul>
                            <!-- pagination -->

                        </div>

                    </div><!-- panel -->

                    <div id="external-content"></div>

                </div><!-- /.col -->
                <div class="col-lg-3 col-xs-12">
                    
                </div>
                <!-- ... Your content goes here ... -->

        </div>
    </div>

</div>

<!-- jQuery -->
<script src="/templates/startadmin/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/templates/startadmin/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/templates/startadmin/js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="/templates/startadmin/js/startmin.js"></script>

<!-- Searcher JavaScript -->
<script src="/dev/webscrapper/view/js/search.js"></script>

</body>
</html>
