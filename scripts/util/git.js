import shell from 'shelljs';
import { delay } from './delay.js';

export const gitCreateVersionBranch = async (version) => {
    if (shell.exec(`git checkout -b version/${version}`).code !== 0) {
        shell.echo('Error: git create branch failed');
        shell.exit(1);
    }

    await delay();
}

export const gitAddVersionFiles = async () => {
    if (shell.exec(`git add .`).code !== 0) {
        shell.echo('Error: git add files failed');
        shell.exit(1);
    }

    await delay();
}

export const gitCommitVersionFiles = async (version) => {
    if (shell.exec(`git commit -m "version bump ${version}"`).code !== 0) {
        shell.echo('Error: git commit failed');
        shell.exit(1);
    }

    await delay();
}

export const gitPushVersionFiles = async (version) => {
    if (shell.exec(`git push --set-upstream origin version/${version}`).code !== 0) {
        shell.echo('Error: git push failed');
        shell.exit(1);
    }

    await delay();
}

export const ghVersionPullRequest = async (version) => {
    if (shell.exec(`gh pr create --title "Version Bump ${version}" --body "Updates version files"`).code !== 0) {
        shell.echo('Error: to create pull request');
        shell.exit(1);
    }

    await delay();
}

