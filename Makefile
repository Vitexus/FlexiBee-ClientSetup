dimage:
	docker build -t vitexsoftware/flexibee-client-setup .
drun: dimage
	docker run -i -t vitexsoftware/flexibee-client-setup


