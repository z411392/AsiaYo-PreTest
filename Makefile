include .env
export

.PHONY: dev test format build
dev:
	@composer run dev
test:
	@php artisan test
format:
	@./vendor/bin/pint
build:
	@docker build . -t app --progress=plain