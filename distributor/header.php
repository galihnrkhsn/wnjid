<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
  
</head>


<div class="container mix" >
    <nav class="navbar navbar-custom ">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="http://mitra.wanoja.com/index.php">WANOJA HIJAB</a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                   
                </button>
                
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav navbar-right">
                    
                   <li class="active"><a href="transaksi.php">Transaksi <span class="sr-only"></span></a></li>
                    <li class="active"><a href="help.php">Tutorial <span class="sr-only"></span></a></li>
                    <li class="active"><a href="logout.php">Logout <span class="sr-only"></span></a></li>
                    
                </ul>
            </div>
        </div>
    </nav>
</div>

<style>
.mix {
    min-height:100px;
}

nav.navbar-custom { 
 background: #488cf6; 
 border-color: #488cf6; 
 box-shadow: 0 0 12px 0 #ccc; 
}
nav.navbar-custom a { 
    color: #dfe0ed; 
}
nav.navbar-custom ul.navbar-nav a { 
    color: #dfe0ed; 
    border-style: solid; 
    border-width: 0 0 2px 0; 
    border-color: #fff; 
}
    
nav.navbar-custom ul.navbar-nav a:hover,
nav.navbar-custom ul.navbar-nav a:visited,
nav.navbar-custom ul.navbar-nav a:focus,
nav.navbar-custom ul.navbar-nav a:active { 
    background: #5CB85C; 
}
nav.navbar-custom ul.navbar-nav a:hover { 
    border-color: #5CB85C; 
}
nav.navbar-custom li.divider { 
    background: #5CB85C; 
}
nav.navbar-custom button.navbar-toggle { 
    background: #5CB85C; 
    border-radius: 2px; 
}
nav.navbar-custom button.navbar-toggle:hover { 
    background: #999; 
}
nav.navbar-custom button.navbar-toggle > span.icon-bar { 
    background: #fff; 
}
nav.navbar-custom ul.dropdown-menu { 
    border: 0; 
    background: #fff; 
    border-radius: 4px; 
    margin: 8px 0; 
    border: 2px solid #5CB85C;
    box-shadow: 0 0 4px 0 #ccc; 
}
nav.navbar-custom ul.dropdown-menu > li > a { 
    color: black; 
}
nav.navbar-custom ul.dropdown-menu > li > a:hover { 
    background: #5CB85C; color: #fff; 
}
nav.navbar-custom span.badge { 
    background: #488cf6; 
    font-weight: normal; 
    font-size: 11px;
     margin: 0 4px; 
}
nav.navbar-custom span.badge.new { 
    background: rgba(255, 0, 0, 0.8); color: #fff; 
}

</style>