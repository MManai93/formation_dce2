function loadNewComments()
{
    var news = $('.comment:last').attr('news-id');
    var comment = $('.comment:last').attr('comment-id');
    var author;
    var email;
    var hreference;
    console.log(news);
    console.log(comment);
    jQuery.post("getNewComments.html", {news_id: news, comment_id_last: comment},function(data){
        console.log(data);


        $.each(data,function(key,value) {
            console.log(value);
            if (value.ghost_author != null) {
                email = value.ghost_email;
                author = value.ghost_author;
                hreference = '/profil-ghost' + ghost_author + '.html';
            }
            else {
                email = value.member_email;
                author = value.member_login;
                hreference = '/admin/profil-' + value.member_id + '.html';
            }
            /*passer les attributs de href et ghost (true ou false) dans les attributs de la balise fieldset*/
            $('<fieldset></fieldset>').insertAfter($('.comment:last'))
                .addClass("comment")
                .attr('comment-id', value.comment_id)
                .attr('news-id', value.news_id)
                .append('<legend>qffe</legend>')

            /*$('<fieldset></fieldset>')
             .addClass('comment')
             .attr('data-id',comment.id)
             .append(
             $('<legend></legend>')
             .append(
             'Poste par ',
             $('<a></a>')
             .attr('href','/member-' + comment.auteur + '.html')
             .html( comment.auteur)
             .css('font-weight','bold'),
             ' le ' + comment.date
             ),
             $('<p></p>')
             .html(comment.contenu)*/
        })

    },"json");

    //setTimeout(loadNewComments,5000);

}