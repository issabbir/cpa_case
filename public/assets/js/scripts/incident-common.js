/*$(".alert").fadeTo(2000, 2000).slideUp(500, function(){
    $(".alert").slideUp(500);
});*/

/*--Text Editor Start---*/
//editor Option
var toolbarOptions = [
    ['bold', 'italic', 'underline'],        // toggled buttons
    [{'list': 'ordered'}, {'list': 'bullet'}],
    [{'align': []}],
    [{'size': ['small', false, 'large', 'huge']}]  // custom dropdown
];

var quill1 = new Quill('#editor1', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow',
});
var quill2 = new Quill('#editor2', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});
var quill3 = new Quill('#editor3', {
    modules: {
        toolbar: toolbarOptions,
    },
    theme: 'snow'
});
var quill4 = new Quill('#editor4', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow',
});

var incidenceDescriptionEditor = new Quill('#incidence_description_editor', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});

var incidenceDescriptionBnEditor = new Quill('#incidence_description_bn_editor', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});

function buttonShowClearTextAreaAndEditor() {
    $(".ql-toolbar").append('<span class="ql-formats bx-pull-right"><button type="button" style="width:50px;" class="clearDescription">Clear</button></span>');
}

$(document).ready(function () {
    buttonShowClearTextAreaAndEditor();
});

function replacePtagToBrTag(editor) {
    $('p').each(function () {
        editor = editor.replace("<p>", " ");
        editor = editor.replace("</p>", "<br>");
    });
    return editor;
}

/*Text Editor Start*/


/** Top bar All News Modal Js Start*/

$(".dynamicModal").on("click", function () {
    var news_id=this.getAttribute('news_id');
    $.ajax(
        {
            type: 'GET',
            url: '/get-top-news',
            data: {news_id:news_id},
            dataType: "json",
            success: function (data) {
                $("#dynamicNewsModalContent").html(data.newsView);
                $('#dynamicNewsModal').modal('show');
            }
        }
    );
});
