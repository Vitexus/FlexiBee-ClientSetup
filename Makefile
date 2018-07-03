dimage:
	docker build -t vitexsoftware/flexibee-client-setup .
drun: dimage
	docker run --publish-all -i -t vitexsoftware/flexibee-client-setup



