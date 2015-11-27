@ECHO OFF
call vendor\bin\doctrine.bat orm:convert-mapping --namespace="Application\Entity\\" --from-database annotation src/
call vendor\bin\doctrine.bat orm:generate-entities --update-entities --generate-annotations=true ./src