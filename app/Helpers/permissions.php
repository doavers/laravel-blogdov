<?php 

function check_user_permissions($request, $actionName = NULL, $id = NULL)
{
	// Get current user
	$currentUser = $request->user();

	// Get current action name
	// dd($request->route()->getActionName());
	if ($actionName) {
		$currentActionName = $actionName;
	}
	else {
		$currentActionName = $request->route()->getActionName();
	}
	list($controller, $method) = explode('@', $currentActionName);
	$controller = str_replace(["App\\Http\\Controllers\\Backend\\", "Controller"], "", $controller);
	// dd("C: $controller M: $method");

	$crudPermissionsMap = [
		// 'create' => ['create', 'store'],
		// 'update' => ['edit', 'udpate'],
		// 'delete' => ['destroy', 'restore', 'forceDestroy'],
		// 'read'   => ['index', 'view'],
		'crud'   => ['create', 'store', 'edit', 'udpate', 'destroy', 'restore', 'forceDestroy', 'index', 'view'],
	];

	$classesMap = [
		'Blog' => 'post',
		'Category' => 'category',
		'Users'	=> 'user'
	];

	foreach($crudPermissionsMap as $permission => $methods)
	{
		// If the current method exists in method list,
		// we'll check the permission
		if(in_array($method, $methods) && isset($classesMap[$controller]))
		{
			$className = $classesMap[$controller];
			// dd("{$permission}-{$className}");

			if ($className == 'post' && in_array($method, ['edit', 'update', 'destroy', 'restore', 'forceDestroy'])) {
				// dd("Current user try to edit/delete a post");
				$id = !is_null($id) ? $id : $request->route("blog");
				// If the current user has not update-others-post/delete-others-post permission
				// make sure he/she only modify his/her own post
				if ($id && (!$currentUser->can('update-others-post') || !$currentUser->can('delete-others-post'))) {
					$post = \App\Post::withTrashed()->find($id);
					if($post->author_id !== $currentUser->id) {
						// dd("Cannot update/delete others post");
						return false;
					}
				}
			}

			// If the user has not permission don't allow next request
			if(!$currentUser->can("{$permission}-{$className}")) {
				// abort(403, "Forbidden access!");
				return false;
			}
			break;
		}
	}

	return true;
}