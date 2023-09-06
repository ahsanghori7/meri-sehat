<!DOCTYPE html>
<html>
<head>
<title>How To Use Summernote Editor In Laravel 8 - Tutsmake.com</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-ko-KR.min.js"></script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-8 offset-2 mt-5">
<div class="card">
<div class="card-header bg-info">
<h6 class="text-white">Laravel Summernote Example Tutorial</h6>
</div>
<div class="card-body">
<form method="post" action="{{ url('posts') }}" enctype="multipart/form-data">
@csrf
<div class="form-group">
<label>Title</label>
<input type="text" name="title" class="form-control"/>
</div>  
<div class="form-group">
<label><strong>Description :</strong></label>
<textarea class="summernote" name="description"></textarea>
</div>
<div class="form-group text-center">
<button type="submit" class="btn btn-success btn-sm">Save</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
$('.summernote').summernote();
});
</script>
</body>
</html>