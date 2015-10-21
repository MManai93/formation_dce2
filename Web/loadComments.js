/*var _$news = null;


function getNewsId()
{
    if( _$news == null)
    {
        _$news=
    }
    accès avec $('#data').data('news_id');
    accès avec $('#data').data('groupe_user');
    accès avec $('#data').data('id_user');
    modifier afin de prendre en compte loadNewComments et loadMoreComments
}*/

function loadComments(eleId)
{
    var elt_data=$(".data");
    var news=elt_data.data("news_id");
    var comment_id_last=null;//serront séttés correctement avec loadNewComments et loadMoreComments
    var comment_id_old=null;
    var affichagedate;
    var RightToModify;
    var elt_balise;
    if(eleId=='show_new')
    {
        if ($('.comment:last').attr('comment-id') !=null)
        {
            comment_id_last=$('.comment:last').attr('comment-id');
        }
    }
    if (eleId=='show_more')
    {
        comment_id_old=$('.comment:first').attr('comment-id');
    }
    console.log(comment_id_last);
    console.log(comment_id_old);
    console.log(news);
    jQuery.post("getComments.html", {news_id: news, comment_id_last: comment_id_last, comment_id_old: comment_id_old},function(data){
        console.log(data);
        $.each(data,function(key,value) {
            affichagedate=' le '+value.dateAdd;
            if(value.isModified==true)
            {
                affichagedate+='<em><small> Modifiee le '+value.dateModif+'</small></em>' ;
            }

            if(elt_data.data('groupe_user')==1 || (elt_data.data('groupe_user')==2 && elt_data.data('id_user')==value.member_id))
            {
                RightToModify=' - <a href="admin/comment-update-'+value.comment_id+'.html">Modifier</a> | <a href="admin/comment-delete-'+value.comment_id+'.html">Supprimer</a>'
            }
            if(eleId=='show_more')
            {
                $('.alert_5_last_comments').html('').append('Liste de tous les commentaires associes a la news')
            }
            if(eleId=='show_new')
            {
                if(comment_id_last==null)
                {
                    $('.no_comment').html(' ')
                        .addClass("comment")
                        .attr('comment-id', value.comment_id)
                        .attr('news-id', value.news_id)
                }
                elt_balise=$('<fieldset></fieldset>').insertAfter($('.comment:last'));
            }
            if (eleId=='show_more')
            {
                elt_balise=$('<fieldset></fieldset>').insertBefore($('.comment:first'));
            }
            elt_balise.addClass("comment")
                .attr('comment-id', value.comment_id)
                .attr('news-id', value.news_id)
                .append('<legend>Poste par <strong><a href="'+value.author_profil_url+'"> '+value.author+'</a></strong> '+affichagedate+'<em> '+value.email+'</em> '+RightToModify+'</legend>')
                .append('<p>'+value.content+'</p>') //changer pour permettre de changer les balises à tous moment
        })
            if(eleId=="show_more")
            {
                document.getElementById('show_more').style.visibility='hidden';
            }
    },"json");

}

/*loadMoreComment(){

    var
        ;
    load
}

loadNewComment()


setInterval*/