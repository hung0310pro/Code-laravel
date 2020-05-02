{{--phân quyền có cho xem hay k  view này là hàm bên App\Policies\CommentPolicy, nếu mà để là cannot thì tk này sẽ k xem đc gì luôn --}}

@can('view', $comment)
    <p>Id User : <?= $comment->id_users ?></p>
    <p>Comment : <?= $comment->comment ?></p>
@endcan