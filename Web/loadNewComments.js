function loadNewComments()
{
    var news = $('.comment:last').attr('news-id');
    var comment = $('.comment:last').attr('comment-id');
    var author;
    var email;
    var hreference;
    var affichagedate;
    console.log(news);
    console.log(comment);
    jQuery.post("getNewComments.html", {news_id: news, comment_id_last: comment},function(data){
        console.log(data);
        $.each(data,function(key,value) {
            console.log(value);
            if (value.ghost_author != null)
            {
                email = value.ghost_email;
                author = value.ghost_author;
                hreference = '/profil-ghost-' + value.ghost_author + '.html';
            }
            else
            {
                email = value.member_email;
                author = value.member_login;
                hreference = '/admin/profil-' + value.member_id + '.html';
            }
            if(value.dateAdd.date!=value.dateModif.date)
            {
                affichagedate='le'+change(value.dateAdd.date)+'<em><small> Modifiée le '+change(value.dateModif.date)+'</small></em>' ;//enlever change
            }
            else
            {
                affichagedate='le '+change(value.dateAdd.date);
            }
            /*passer les attributs de href et ghost (true ou false) dans les attributs de la balise fieldset*/
            $('<fieldset></fieldset>').insertAfter($('.comment:last'))
                .addClass("comment")
                .attr('comment-id', value.comment_id)
                .attr('news-id', value.news_id)
                .append('<legend>Poste par <strong><a href="'+hreference+'"> '+author+'</a></strong> '+affichagedate+'<em> '+email+'</em></legend>')/*format date*/
                .append('<p>'+value.content+'</p>')
        })

    },"json");

    //setTimeout(loadNewComments,5000);
}
