#### When submitting a pull request

1. Run the linter.

   ```bash
   make lint
   ```

1. Run the tests.

   ```bash
   make test
   ```

1. Carefully _read your diff_ before submitting a PR.
    * Make sure your diff clearly represents your changes.
    * Make sure your code is easy for reviewers to follow.
    * Can your code review be broken into smaller chunks?
    * Make sure you have PHPUnit tests that cover your changes.

1. Make sure that your changes are very-nearly complete.
    * Small changes/fixes based on feedback are OK.
    * Major changes should be avoided while a PR is open. If you need to make major changes, close the PR, make your changes, and open a new PR once you're ready.

1. In the description, start with an explanation of the change and why you want to make it.
    * Make any relevant documentation easily available for reviewers.

1. Add a list of changes you've made using [task list](https://help.github.com/articles/basic-writing-and-formatting-syntax/#task-lists) syntax.
    * Things that are complete should have `[X]`.
    * Things that are still incomplete should have `[ ]`.

1. Discuss feedback.
    * The _SimplePie NG Core Team_ has the final say on what gets accepted.
    * Respond to all code review feedback.
    * _Respectfully_ discuss any feedback you disagree with. You may need to bring a _better_ argument to the table to convince us.

1. If you don't understand something, _ask_.
