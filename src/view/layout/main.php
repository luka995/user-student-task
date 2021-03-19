<!DOCTYPE html>
<html lang="en">
    
<head>
    <title>School Board</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>  
</head>
<body>

    <div class="jumbotron text-center" style="margin-bottom:0; color:white; background-color: #e31b23;">
        <h1>School Board</h1>
        <p>Quantox</p> 
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="fas fa-home"></i> Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
          <ul class="navbar-nav mr-auto">

          </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php?controller=student&action=create">New Student</a>
            </li>        
            <li class="nav-item">
              <a class="nav-link" href="index.php?controller=board&action=create">New Board</a>
            </li>
        </ul>
      </div>  
      </div>    
    </nav>

    <div class="container" style="margin-top:30px">
      <div class="row">
        <div class="col-sm-3">
            Side menu
        </div>
        <div class="col-sm-9">
            <?=$content?> 
        </div>
      </div>
    </div>
</body>

</html>