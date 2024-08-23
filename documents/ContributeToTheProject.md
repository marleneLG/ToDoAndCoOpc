# Contribute to the project

How to contribute to the project

## Getting started

1. Fork the project’s Github directory. For more information: [GitHub Documentation](https://docs.github.com/fr/pull-requests/collaborating-with-pull-requests/working-with-forks/fork-a-repoy)
2. Clone locally from your fork
3. Follow the [README.md](https://github.com/marleneLG/ToDoAndCoOpc/blob/main/README.md) file to install the project.
4. Create a new branch for the feature and position yourself on that branch.

```shell
git checkout -b <branch-name>
git push origin <branch-name>
```

5. Code according to best practices. Do not hesitate to use the documentation [Symfony Documentation](https://symfony.com/doc/current/index.html) [PSR PHP](https://www.php-fig.org/psr/)
6. Open a pull request on the project’s Github directory

## Run tests

Execute this command :
```shell
 vendor/bin/phpunit --coverage-html public/test-coverage
```
For more informations [Symfony Documentation Testing](https://symfony.com/doc/4.x/testing.html)