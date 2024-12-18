include .env
export

.PHONY: dev test format
dev:
	@composer run dev
test:
	@php artisan test
format:
	@./vendor/bin/pint