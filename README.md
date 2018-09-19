# Laravel Restfull

## Introdução
Sistema para facilitar a criação de APIs restfull com a utilização do Laravel 5.

A aplicação conta com a estrutura base para controllers restfull e repositórios CRUD, permitindo a criação dos endpoints de um recurso apenas com a criação de classes extendendo as classes padrões.

## Estrutura
A aplicação conta, além da estrutura padrão do Laravel 5, com a seguinte estrutura base _(toda a estrutura a seguir encontra-se dentro do namespace APP - pasta __app__ da estrutura do Laravel 5)_:
  - Constansts/HttpStatusCodeConstants.php
  - Contracts/CrudServicesContract.php
  - Exceptions/AppException.php
  - Exceptions/Handler.php (classe padrão do Laravel 5)
  - Helpers/MakeRequestHelper.php
  - Http/Controllers/RestfullController.php
  - Services/CrudService.php

### HttpStatusCodeConstants
Classe ___abstrata___ para controlle do status HTTP das respostas da aplicação.
Conta com as constantes:
  - __OK__ = 200
  - __CREATED__ = 201
  - __UNAUTHORIZED__ = 401
  - __FORBIDDEN__ = 403
  - __NOT_FOUND__ = 404
  - __METHOD_NOT_ALLOWED__ = 405
  - __UNPROCESSABLE_ENTITY__ = 422
  - __INTERNAL_SERVER_ERROR__ = 500

### CrudServicesContract
Interface que deverá ser implementada pelos serviços que serão vinculados a ela no __RestfullController__.
### AppException
Classe que extende a classe de exceção padrão do Laravel 5, usada apenas para identificar exceções lançadas pela própria aplicação.
### Handler
Classe padrão do Laravel 5, editada para lidar com todas as exceções e enviar a resposta com os devidos código HTTP e mensagem. 
### MakeRequestHelper
Classe __abstrata__ criada para lidar com a chamadas a serviços dentro dos controllers. A classe conta com o método ___sendRequest___ que deve ser chamado nos controllers e garante a execução de toda a chamada dentro de uma única transação com o banco, garantido que a base de dados não seja alterada em caso de erro.
### RestfullController
Classe __abstrata__ criada para fornecer os métodos restfull dos controlles para as classes que a extenderem. A classe extende o controller padrão do Laravel, permitindo às classes filhas utilizarem todos os recursos de um controller padrão. A classe fornece os métodos:
 - __index__  
 - __store__
 - __show__
 - __update__
 - __destroy__
### CrudService
Classe __abstrata__ criada para utilizar o Eloquent do Laravel 5 e já fornecer todos os métodos CRUD para os serviços que a extenderem. A classe fornece os métodos:
 - __index__  
 - __create__
 - __read__
 - __update__
 - __delete__
## Exemplo
Um exemplo do uso é apresentado na branch ___feature/testing___.
As classes criadas/editadas para o exemplo são:
 - __app/ModelTest.php__ _(Model criado pelo Laravel)_
 - __app/Controllers/ModelTestController.php__ _(Controller criado pelo Laravel e extende o RestfullController)_
 - __app/Providers/AppServiceProvider.php__ _(Provider criado pelo Laravel, editado para fazer o bind da inteface CrudServicesContract com o serviço do recurso)_
 - __app/Services/ModelTestService.php__ _(Serviço criado para o recurso e extende o CrudService)_
 - __database/factories/ModelTestFactory.php__ _(Factory criado pelo Laravel para o seed da base para testes)_
 - __database/migrations/2018_09_14_174035_create_model_tests_table.php__ _(Migration criada pelo laravel para a criação da tabela do recurso na base)_
 - __database/seeds/ModelTestTableSeeder.php__ _(Seeder criado pelo Laravel para o seed da base para testes)_
 - __App/ModelTest.php__ _(Model criada pelo Laravel)_