<h2>Images</h2>
<hr />

<div class="row">
    <div class="col-lg-6">

        <h4>Add a Wallpaper</h4>
        
        <form id="image-form">

            <div class="form-group">
                <label for="category">Select Category</label>
                <select id="category" class="form-control">

                </select>
            </div>

            <div class="form-group">
                <label for="title">Wallaper Title</label>
                <input type="text" class="form-control" id="title" />
                <div class="invalid-feedback">
                    Please enter title 
                </div>
            </div>
			
			<div class="form-group">
                <label for="featured">Select is Featured?</label>
                <select id="featured" class="form-control">
				<option value="Yes">Yes</option>
				<option value="No">No</option>

                </select>
            </div>

            <div class="form-group">
                <label for="desc">Description</label>
                <input type="text" class="form-control" id="desc" value="G-Developers" disabled/>
                <div class="invalid-feedback">
                    Please enter description 
                </div>                    
            </div>

            <div class="form-group">
                <label for="wallpaper">Wallaper Image</label>
                <input type="file" class="form-control" id="wallpaper" />

                <div class="invalid-feedback">
                    Please choose a valid image
                </div>
            </div>

            <div class="form-group">
                <div class="progress">
                    <div class="progress-bar" id="progress" style="width:0%">0%</div>
                </div>
            </div>

            <div class="form-group">
                <button type="button" id="btn-save" class="btn btn-primary">Save Wallpaper</button>
            </div>

        </form>

    </div>

    <div class="col-lg-6">
        <img id="img-wallpaper" width="250" height="500" />
    </div>
</div>

<script>
    function previewWallpaper(thumbnail){
        if(thumbnail.files && thumbnail.files[0]){
            var reader = new FileReader(); 

            reader.onload = function(e){
                $("#img-wallpaper").attr('src', e.target.result);
            }
            reader.readAsDataURL(thumbnail.files[0]);
        }
    }

    $("#wallpaper").change(function(){
        previewWallpaper(this);
    });

    var dbCategories = firebase.database().ref("categories");

    dbCategories.once("value").then(function(categories){

        categories.forEach(function(category){
            $("#category").append("<option value='"+category.key+"'>"+category.key+"</option>");     
        });
    });

    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];

    $("#btn-save").click(function(){
        $("#title").removeClass("is-invalid");
        $("#desc").removeClass("is-invalid");
        $("#wallpaper").removeClass("is-invalid");

        var title = $("#title").val();
        var desc = $("#desc").val(); 
		var featured = $("#featured").val();
        var wallpaper = $("#wallpaper").prop("files")[0];

        if(!title){
            $("#title").addClass("is-invalid");
            return; 
        }

        if(!desc){
            $("#desc").addClass("is-invalid");
            return; 
        }

        if(!wallpaper){
            $("#wallpaper").addClass("is-invalid");
            return; 
        }

        if($.inArray(wallpaper["type"], validImageTypes)<0){
            $("#wallpaper").addClass("is-invalid");
            return; 
        }

        var category = $("#category").val(); 
        var name = wallpaper["name"];

        var ext = name.substring(name.lastIndexOf("."), name.length);

        var imagename = new Date().getTime(); 

        var storageRef = firebase.storage().ref(category + "/" + imagename + ext);

        var uploadTask = storageRef.put(wallpaper);

        uploadTask.on("state_changed", 
            function progress(snapshot){
                var percentage = (snapshot.bytesTransferred / snapshot.totalBytes) * 100; 
                $("#progress").html(Math.round(percentage)+"%");
                $("#progress").attr("style", "width: "+percentage + "%");
            }, 

            function error(err){

            },

            function complete(){
                uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                    var database = firebase.database().ref("HDWallpaper");

                    var imageid = database.push().key;

                    var image = {
                        "wallpaperImage": downloadURL, 
                        "wallpaperName": title,
                        "wallpaperCategory": category,
                        "wallpaperFeatured": featured,											
                        "developersName" : desc,
                        "id" : <?php echo(rand(10,1000)); ?>,
                        "wallpaperViews":  <?php echo(rand(10,1000)); ?>,
                        "wallpaperSets" : <?php echo(rand(10,1000)); ?>,
                        "wallpaperDownloads" : <?php echo(rand(10,1000)); ?>
				
                    };

                    database.child(imageid).set(image, function(err){
                        alert("Image saved..");
                        resetForm();
                    });
                }); 
            }
        );

    });
    
    function resetForm(){
       $("#image-form")[0].reset(); 
       $("#img-wallpaper").attr("src", "");;
       $("#progress").html("Completed");
    }
</script>