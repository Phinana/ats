<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
</head>

<body>
	
<div class="col-lg-12">
		<div class="main-box clearfix">

			<div class="table-responsive">
				<table class="table user-list" id="tbl_movie">
					<thead>
						<tr>
							<th><span>User</span></th>
							<th><span>Created</span></th>
							<th class="text-center"><span>Status</span></th>
							<th><span>Email</span></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<ul class="pagination pull-right">
				<li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
				<li><a href="#">1</a></li>
				<li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
			</ul>

				<ul class="pagination pull-left">
					<li>
						<div class="justify-content-center">
                    		<button type="button" class="btn btn-warning" id="button_fetch_data">Fetch Data</button>
                		</div>
					</li>
					<li>
						<div class="justify-content-center">
                    		<button type="button" class="btn btn-warning" id="button_Insert_data">Insert Data</button>
                		</div>
					</li>
				</ul>
		</div>
	</div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>

var limit = 10;
var off_set = 0;

//Sayfanın açılmasıyla beraber listeyi yeniler
$(document).ready(function(){
	update_tbl_movie();
});

//Fetch data butonunun tıklanması için
$("#button_fetch_data").click(function(){
	update_tbl_movie();    
});

$("#button_Insert_data").click(function(){
	$.ajax({
        url: "InsertMovies.php",
        method: "POST", 
        data: {},
        success: function(result){
			update_tbl_movie();
		}});
});

$("#search_filter").click(function(){
	activate_filter();
});

$("#tbl_movie tbody").on("click","tr td .user-link", function(){
	var movie_id = $(this).data("movie-id");
	if(!$("#movie_"+ movie_id).hasClass("editing")){
		var link = create_link($(this).text().replace('.',''), $("#tbl_movie tbody tr td #movie_td_progress_" + movie_id +" .progress_text").text());
		window.location.replace(link);
	}
});

$(document).on('change', '#search_1', function() {
	activate_filter();
});

function activate_filter(){
	var search_text = $('#search_1').val();
	console.log(search_text);

	if(search_text != ""){
		var movies = $("#tbl_movie tbody tr");
		movies.each(function(index) {
		var temp = $(this).data('movie-text').toLowerCase();
			if(!temp.includes(search_text.toLowerCase())) {
				$( this ).hide();
			}else{
				$( this ).show();
			}
		});
	}else{
		var movies = $("#tbl_movie tbody tr");
		movies.each(function(index) {
			$(this).show();
		});
	}
}

//table içerisindeki td'lerin içerisinde bulunan delete_button'ların tıklanmasını sağlar
$("#tbl_movie tbody").on("click","tr td a #delete_button", function(){
	var movie_id = $(this).data("value-id");
	$.ajax({
        url: "DeleteMovies.php",
        method: "POST",
        data: {movie_id: movie_id},
        success: function(){
			update_tbl_movie();
			console.log("Successfully deleted");
	}});
});



//table içerisindeki td'lerin içerisinde bulunan update_button'ların tıklanmasını sağlar
$("#tbl_movie tbody").on("click","tr td a #update_button", function(){
	var id = $(this).data("value-id");
	var title = $("#tbl_movie tbody tr td #movie_td_title_"+ id);
	var episodes = $("#tbl_movie tbody tr td #movie_td_episodes_"+ id);
	var progress = $("#tbl_movie tbody tr td #movie_td_progress_"+ id +" .progress_text");
	var movie = $("#tbl_movie tbody #movie_"+ id);
	var movie_image = $("#tbl_movie tbody tr td #movie_td_image_"+ id);
	var image_link = $("#tbl_movie tbody tr td #movie_td_image_link_"+ id);

	var editable = title.attr('contenteditable');
	console.log("Content editable = "+ editable);
	if(editable == 'false'){
		movie.removeClass("show").addClass("editing");
		title.attr('contenteditable', 'true');
		episodes.attr('contenteditable', 'true');
		progress.attr('contenteditable', 'true');
		image_link.attr('contenteditable', 'true');

		var image_id = "movie_td_image_"+ id;
		$('#tbl_movie tbody tr td #movie_td_image_link_'+ id).show();
	}
	if(editable == 'true'){
		movie.removeClass("editing").addClass("show");
		title.attr('contenteditable', 'false');
		episodes.attr('contenteditable', 'false');
		progress.attr('contenteditable', 'false');
		image_link.attr('contenteditable', 'false');

		var image_id = "movie_td_image_"+ id;
		$('#tbl_movie tbody tr td #movie_td_image_link_'+ id).hide();

		var image = image_link.text();

		$.ajax({
        url: "UpdateMovies.php",
        method: "POST", 
        data: {movie_id: id, movie_title: title.text(), movie_episodes: episodes.text(), movie_progress: progress.text(), movie_image: image},
        success: function(result){
			var parsed_result = JSON.parse(result);
			movie_image.attr("src", parsed_result);
		}});
	}
});

//tbl_movie tablosunun tıklanabilir olmasını sağlar
$("#tbl_movie tbody").on("click","#movie", function(){
	var movie_id = $(this).data("movie-id");
	console.log(movie_id);
});


function create_link(title, progress){
	var split = title.split(/[-_:;\s]+/);
	var link = "https://www.turkanime.co/video/";
	$.each(split, function (key, value){
		link += split[key] + "-";
	});

	link += progress + "-bolum";
	return link;
}

//tbl_movie tablosunu temizler ve içini tekrar doldurur
function update_tbl_movie(){
	$("#tbl_movie tbody").empty();

	$.ajax({
        url: "GetMovies.php",
        method: "POST", 
        data: {limit: limit, off_set: off_set},
        success: function(result){
			var parsed_result = JSON.parse(result);
			var tbl_name = "tbl_movie";
			var html_string = "";

			for(let x = 0; x < parsed_result.length; x++){
				html_string += getMovieRowString(parsed_result[x]["id"], parsed_result[x]["title"], parsed_result[x]["episodes"], parsed_result[x]["progress"], parsed_result[x]['image']);
			}
			addRowToTable(tbl_name, html_string);     
  }});
}

//Movie tablosuna eklemek için html kodları içeren bir string üretir
function getMovieRowString(id, title, episodes, progress, image){
	var html_string = "";

			html_string += "<tr id='movie_"+ id +"' data-movie-text='"+ title +"' data-movie-id="+ id +" class='show'>";

				html_string += "<td>";
					html_string += "<img id='movie_td_image_"+ id +"' src='"+ image +"' data-movie-id="+ id +" alt=''>";
					html_string += "<a href='#' id='movie_td_title_"+ id +"' class='user-link' data-movie-id="+ id +" contenteditable='false'>"+ title +"</a>";
					html_string += "<span id='movie_td_episodes_"+ id +"' class='user-subhead' contenteditable='false'>"+ episodes +"</span>";
					html_string += "<span id='movie_td_image_link_"+ id +"' class='user-subhead-image-link' contenteditable='false'>"+ image +"</span>";
				html_string += "</td>";

				html_string += "<td>";
					html_string += "2013/08/08";
				html_string += "</td>";

				html_string += "<td class='text-center'>";

					html_string += "<a href='#' class='table-link'>";
						html_string += "<span data-value-id="+ id +" class='fa-stack'>";
							html_string += "<i class='fa fa-square fa-stack-2x'></i>";
							html_string += "<i class='fa fa-minus fa-stack-1x fa-inverse'></i>";
						html_string += "</span>";
					html_string += "</a>";

					html_string += "<span id='movie_td_progress_"+ id +"' class='label label-default' contenteditable='false'>Episode <span class='progress_text'>"+ progress +"</span></span>";

					html_string += "<a href='#' class='table-link'>";
						html_string += "<span data-value-id="+ id +" class='fa-stack'>";
							html_string += "<i class='fa fa-square fa-stack-2x'></i>";
							html_string += "<i class='fa fa-plus fa-stack-1x fa-inverse'></i>";
						html_string += "</span>";
					html_string += "</a>";

				html_string += "</td>";

				html_string += "<td>";
					html_string += "<a href='#'>mila@kunis.com</a>";
				html_string += "</td>";

				html_string += "<td>";
					html_string += "<a href='#' class='table-link'>";
						html_string += "<span data-value-id="+ id +" class='fa-stack'>";
							html_string += "<i class='fa fa-square fa-stack-2x'></i>";
							html_string += "<i class='fa fa-search-plus fa-stack-1x fa-inverse'></i>";
						html_string += "</span>";
					html_string += "</a>";

					html_string += "<a href='#' class='table-link'>";
						html_string += "<span id='update_button' data-value-id="+ id +" class='fa-stack'>";
							html_string += "<i class='fa fa-square fa-stack-2x'></i>";
							html_string += "<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>";
						html_string += "</span>";
					html_string += "</a>";

					html_string += "<a href='#' class='table-link danger'>";
						html_string += "<span id='delete_button' data-value-id="+ id +" class='fa-stack'>";
							html_string += "<i class='fa fa-square fa-stack-2x'></i>";
							html_string += "<i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>";
						html_string += "</span>";
					html_string += "</a>";
				html_string += "</td>";

			html_string += "</tr>";
			
			return html_string;
}


//Verilen Stringi verilen tabloya ekler
function addRowToTable(tbl_name, html_string){
	$("#"+ tbl_name +" tbody").append(html_string);
}
</script>