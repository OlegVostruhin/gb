$(function(){
    $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
    });

    $('#form-data').submit(function(e){

       let route = $('#form-data').data('route');
       let form_data = $(this);
       let postId = $("#post_text").data("post-id");

       if (postId == null) {
           createPost(route, form_data.serialize());
       } else {
           route = "/posts/" + postId;
           updatePost(route, form_data.serialize(), postId);
       }

       e.preventDefault();
    });

    function blockEditDeleteInclude(postId) {
        let path = window.location.href + "posts/";
        let blockEditDelete = $("<div class='col-md-4 text-right'>" +
            "<div class='btn-group' role='group' aria-label='...'>" +
            "<a href='" + path + postId + "/edit' class='btn btn-warning' data-post-id = '" + postId + "'>Редактировать</a>" +
            "<a href='" + path + postId + "' class='btn btn-danger' data-post-id = '"+ postId +"'>Удалить</a>" +
            "</div>" +
            "</div>");
        return blockEditDelete;
    }

    //Update Post
    function updatePost(route, request, postId) {
        $.ajax({
           type: "PUT",
           url: route,
           data: request,
           success: function (Response) {
               let editedPost = $(".post_list a.btn-warning[data-post-id = " + postId + "]").closest(".post");
               let textArea = $("#post_text");
               editedPost.find("p").text(textArea.val());
               textArea.removeData("post-id");
               textArea.val("");
           }
        });
    }

    //Create Post
    function createPost(route, request){
        $.ajax({
            type: 'POST',
            url: route,
            data: request,
            success: function (Response) {
                console.log(Response);
                let divEditDelete;
                let divContent = $("<div/>",{"class": "col-md-8"})
                    .append("<h4>" + Response.user + "</h4>")
                    .append("<h6>" + Response.date + "</h6>")
                    .append("<p>" + Response.text + "</p>");
                if (Response.user !== "Аноним") {
                    divEditDelete = blockEditDeleteInclude(Response.post_id);
                }
                let divRow = $("<div/>",{"class": "row"})
                    .append(divContent)
                    .append(divEditDelete);

                let divPost = $("<div/>",{"class": "post col-md-12"})
                    .append(divRow).append("<hr>");

                divPost.prependTo(".post_list");

                $("#post_text").val("");
            }
        });
    }

    //Delete Post Action Listener
    $(".post_list").on("click", "a.btn-danger", function (e) {
        let postId = $(this).data("postId");
        let delPost = $(this).closest(".post");
        $.ajax({
            type: "DELETE",
            url: "/posts/" + postId,
            data: postId,
            success: function (Response) {
                delPost.remove();
            }
        });
        e.preventDefault();
    });

    //Edit Post Action Listener
    $(".post_list").on("click", "a.btn-warning" , function (e) {
        let postId = $(this).data("postId");
        $.ajax({
            type: "GET",
            url: "/posts/" + postId + "/edit",
            data: postId,
            success: function (Response) {
                let textArea = $("#post_text");
                textArea.val(Response.text);
                textArea.attr("data-post-id", Response.post_id);
            }
        });
        e.preventDefault();
    });

});
