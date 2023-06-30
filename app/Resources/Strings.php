<?php

namespace App\Resources;

class Strings
{
    /**
     * Common
     */
    const REQUIRED_FIELD_DOES_NOT_VALIDATE_ERROR = '입력 항목을 확인해주세요.';

    /**
     * User
     */
    const USER_ALREADY_EXISTS_EXCEPTION = '이미 존재하는 사용자 이메일입니다.';
    const USER_LOGIN_FAILED_EXCEPTION = '로그인에 실패했습니다.';
    const USER_NOT_FOUND_EXCEPTION = '존재하지 않는 사용자입니다.';
    const SUPER_ADMIN_DELETE_EXCEPTION = '최고 관리자는 삭제할 수 없습니다.';

    /**
     * Uploaded Image
     */
    const UPLOADED_IMAGE_TYPE_ERROR = '유효하지 않은 이미지 타입입니다.';
    const UPLOADED_IMAGE_EXTENSION_ERROR = '처리 할 수 없는 확장자 입니다.';
}
