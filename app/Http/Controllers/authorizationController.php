<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class authorizationController extends Controller
{
	public function index()
	{
		$comment = Comment::findOrFail(1);
		$user = Auth::user();
/*		if (Gate::allows('edit-comment', $comment)) { // (1)
            // có thể viết gì đó ở đây, ví dụ nếu được thì cho chỉnh sửa dữ liệu.
			echo "Ban co quyen chinh sua comment";
		} else {
			echo "Ban khong co quyen chinh sua comment";
		}*/

		if (Gate::forUser($user)->allows('edit-comment', $comment)) {
			// có thể viết gì đó ở đây, ví dụ nếu được thì cho chỉnh sửa dữ liệu.
			echo "Người dùng này được phép chỉnh sửa comment";
		}

		if (Gate::forUser($user)->denies('edit-comment', $comment)) {
			// có thể viết gì đó ở đây, ví dụ nếu k được thì cho k chỉnh sửa dữ liệu.
			echo "Người dùng này không được phép chỉnh sửa comment";
		}

		// (1) cx đc
	}

	public function show($id){
		$comment = Comment::findOrFail($id);
		/*Auth::user()->cannot('');*/
		$this->authorize($comment,'view'); // view này là hàm bên App\Policies\CommentPolicy
		// nếu cái check trong CommentPolicy ok thì trả về cái return bên dưới.
		return view('showauthorizationpolicies',['comment' => $comment]);
	}
}
