# Sudoku Solver

This is an php app which expose an api to solve any sudoku

## Usage

- first install all dependencies
```shell script
composer install
```

- run app, like this
```shell script
php -S localhost:8080
```

- you can call api like this
```shell script
curl -X POST \
  'http://localhost:8080/solve' \
  -H 'Content-Type: application/json; charset=utf-8' \
  -d '{
    "grid": "000000680000073009309000045490000000803050902000000036960000308700680000028000000"
  }'
```
just pass the empty cells with **0**, and all elements in a linear string

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
