# 1. Автор
Ларионов Никита

## 2. Подготовительные действия

### 2.1 Требования
- PHP >= 7.4
- Установленный сервер базы данных (MySQL/PostgreSQL)
- Расширение PDO для PHP (установлено по умолчанию в большинстве окружений)


### 2.2. Установка

1. Склонируйте репозиторий проекта:
   git clone https://github.com/Lars0n5/QueryBuilder.git
   
2. Перейдите в директорию проекта

### 2.3. Настройка

1. Настройте подключение к базе данных, указав правильные параметры в файле конфигурации:

		Пример конфигурации для MySQL:
			$config = [
				'host' => 'localhost',
				'dbname' => 'test_db',
				'username' => 'root',
				'password' => '',
				'type' => 'mysql'
			];
2. Создайте дочерний класс, отнаследовав его от класса DBWrapper и реализуйсте метод connect.
	
	class MySQLWrapper extends DBWrapper {
		protected function connect($config) {
			$dsn = "{$config['type']}:host={$config['host']};dbname={$config['dbname']}";
			try {
				$this->pdo = new PDO($dsn, $config['username'], $config['password']);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				die("Connection failed: " . $e->getMessage());
			}
		}
	}
	
3. Убедитесь, что база данных настроена и запущена.

## 3. Запуск

1. Создайте экземпляр дочернего класса.
	$db = new MyDatabase($config);
2. Реализуйте необходиные методы класса DBWrapper.
3. Запустите проект.
		
		
	
	