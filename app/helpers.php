<?php
use App\Models\UserType;
use App\Models\Permission;

if (!function_exists('check_permission')) {
	function check_permission(string $requiredType, string $requiredArea) {
		$type = UserType::where('id', auth()->user()->type)->first();
		$permissions = $type->permission_ids;
		foreach ($permissions as $perm_ids) {
			$permission = Permission::where('id', $perm_ids)->first();
			if ($requiredType == $permission->permission_type && preg_match("/$requiredArea/", $permission->permission_area))
				return true;
		}
		return false;
	}
}