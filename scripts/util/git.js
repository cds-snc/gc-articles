import shell from 'shelljs';
import { delay } from './delay.js';

export const gitCreateVersionBranch = async (version) => {
    if (shell.exec(`git checkout -b version/${version}`).code !== 0) {
        shell.echo('Error: git create version branch failed');
        shell.exit(1);
    }

    await delay();
}

export const gitAddVersionFiles = async () => {
    if (shell.exec(`git add .`).code !== 0) {
        shell.echo('Error: git add files failed for version bump');
        shell.exit(1);
    }

    await delay();
}

export const gitCommitVersionFiles = async (version) => {
    if (shell.exec(`git commit -m "version bump ${version}"`).code !== 0) {
        shell.echo('Error: git commit failed for version bump');
        shell.exit(1);
    }

    await delay(5000);
}

export const gitPushVersionFiles = async (version) => {
    if (shell.exec(`git push --set-upstream origin version/${version}`).code !== 0) {
        shell.echo('Error: git push failed for version bump');
        shell.exit(1);
    }

    await delay(5000);
}

export const ghVersionPullRequest = async (version) => {
    if (shell.exec(`gh pr create --title "Version Bump ${version}" --body "Updates version files"`).code !== 0) {
        shell.echo('Error: to create pull request for version bump');
        shell.exit(1);
    }

    await delay();
}

export const gitCreateReleaseBranch = async (tag) => {
    if (shell.exec(`git checkout -b release/${tag}`).code !== 0) {
        shell.echo('Error: git create release branch failed');
        shell.exit(1);
    }

    await delay(5000);
}

export const gitAddReleaseFiles = async () => {
    if (shell.exec(`git add .`).code !== 0) {
        shell.echo('Error: git add files failed for release');
        shell.exit(1);
    }

    await delay();
}

export const gitCommitReleaseFiles = async (tag) => {
    if (shell.exec(`git commit -m "release ${tag}"`).code !== 0) {
        shell.echo('Error: git commit failed for release');
        shell.exit(1);
    }

    await delay();
}

export const gitPushReleaseFiles = async (tag) => {
    if (shell.exec(`git push --set-upstream origin release/${tag}`).code !== 0) {
        shell.echo('Error: git push failed for release');
        shell.exit(1);
    }

    await delay();
}

export const ghReleasePullRequest = async (tag, notes) => {
    if (shell.exec(`gh pr create --title "Release ${tag}" --body "${notes}"`).code !== 0) {
        shell.echo('Error: failed to create release pull request');
        shell.exit(1);
    }

    await delay();
}

export const gitCheckoutMain = async () => {
    if (shell.exec(`git checkout main`).code !== 0) {
        shell.echo('Error: failed to checkout main');
        shell.exit(1);
    }

    await delay();
}

export const gitPullLatestFromMain = async () => {
    if (shell.exec(`git pull origin main`).code !== 0) {
        shell.echo('Error: failed to pull latest from main');
        shell.exit(1);
    }

    await delay();
}

export const gitCheckClean = async () => {
    if (shell.exec(`[[ -z $(git status -s) ]]`).code !== 0) {
        shell.echo('Error: you have local changes you should stash or commit');
        shell.exit(1);
    }

    await delay();
}

