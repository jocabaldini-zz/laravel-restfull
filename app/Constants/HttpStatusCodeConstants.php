<?php

namespace App\Constants;

abstract class HttpStatusCodeConstants
{
	const OK = 200;
	const CREATED = 201;

	const UNAUTHORIZED = 401;
	const FORBIDDEN = 403;
	const NOT_FOUND = 404;
	const METHOD_NOT_ALLOWED = 405;
	const UNPROCESSABLE_ENTITY = 422;

	const INTERNAL_SERVER_ERROR = 500;
}