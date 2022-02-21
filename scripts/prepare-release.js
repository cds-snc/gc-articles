import yargs from "yargs";
import inquirer from 'inquirer';
import { updateVersion, updateEnvironmentManifest, updateInfrastructureVersion } from './util/update-files.js';
import { createInfrastructureTagAndPush, createTaggedRelease, getVersionTag } from './util/tag-files.js';
import {
    gitCreateVersionBranch,
    gitAddVersionFiles,
    gitCommitVersionFiles,
    gitPushVersionFiles,
    ghVersionPullRequest,
    gitCreateReleaseBranch,
    gitAddReleaseFiles,
    gitCommitReleaseFiles,
    gitPushReleaseFiles,
    ghReleasePullRequest,
    gitCheckoutMain,
    gitPullLatestFromMain,
    gitCheckClean,
    gitCheckMain,
    gitCreateProductionReleaseBranch,
    gitCommitProductionManifestFile,
    gitPushProductionManifestFile,
    ghProductionReleasePullRequest, gitAddProductionManifestFile, ghInfrastructureReleasePullRequest
} from './util/git.js';
import path from 'path';
import fs from 'fs';
import shell from 'shelljs';

const argv = yargs(process.argv.slice(2)).argv;

const displayPreviousVersion = async (service = 'infrastructure') => {
    let fileName = 'VERSION';
    let filePath = '.';

    if (service === 'infrastructure') {
        filePath = './infrastructure/'
    }

    if (service === 'wordpress') {
        filePath = '.'
    }

    if (service === 'apache') {
        filePath = './wordpress/docker/apache'
    }

    const file = path.join(filePath, fileName);
    const version = await fs.promises.readFile(file, 'utf8');

    shell.echo("Previous version: " + version);
}

const inputVersionNumber = async () => {
    const question = {
        type: 'input',
        name: 'version',
        message: "Version number",

    }
    const answer = await inquirer.prompt(question);
    return answer.version;
}

const inputReleaseTag = async () => {
    const question = [
        {
            type: 'input',
            name: 'notes',
            message: "Release notes i.e. bug fixes",
        }
    ];

    const answer = await inquirer.prompt(question);
    const version = await getVersionTag();
    return { tag: version.tag, notes: answer.notes, version: version.number };
}

(async () => {
    try {
        if (argv.test) {
            await updateEnvironmentManifest('2.2.2');
        }
        if (argv.version_num) {
            await gitCheckMain();
            await gitCheckClean();
            const version = await inputVersionNumber();
            await gitCreateVersionBranch(version);
            await updateVersion(version);
            await gitAddVersionFiles();
            await gitCommitVersionFiles(version);
            await gitPushVersionFiles(version);
            await ghVersionPullRequest(version);
            await gitCheckoutMain();
        }

        if (argv.tag) {
            await gitCheckMain();
            await gitCheckClean();
            await gitPullLatestFromMain();
            const { version, tag, notes } = await inputReleaseTag();
            await gitCreateReleaseBranch(tag);
            await updateEnvironmentManifest(version, 'staging');
            await gitAddReleaseFiles();
            await gitCommitReleaseFiles(tag);
            await gitPushReleaseFiles(tag);
            await ghReleasePullRequest(tag, notes);
            createTaggedRelease(tag, notes);
            await gitCheckoutMain();
        }

        if (argv.production) {
            await gitCheckMain();
            await gitCheckClean();
            await gitPullLatestFromMain();
            const version = await inputVersionNumber();
            await gitCreateProductionReleaseBranch(version);
            await updateEnvironmentManifest(version, 'production');
            await gitAddProductionManifestFile();
            await gitCommitProductionManifestFile(version);
            await gitPushProductionManifestFile(version);
            await ghProductionReleasePullRequest(version);
            await gitCheckoutMain();
        }

        if (argv.deployInfrastructure) {
            await gitCheckMain();
            await gitCheckClean();
            await gitPullLatestFromMain();
            await displayPreviousVersion('infrastructure');
            const version = await inputVersionNumber();
            await updateInfrastructureVersion(version);
            await updateEnvironmentManifest(version, 'production', 'infrastructure');
            await createInfrastructureTagAndPush(version);
            await ghInfrastructureReleasePullRequest(version);
            await gitCheckoutMain();
        }

        console.log("\n---- Success ----\n");
        console.log("        🚀");
        console.log("\n------------------\n");

    } catch (error) {
        console.log("\n---- Error ----\n");
        console.log("       💀");
        console.error(`\n${error.message}`);
        console.log("\n------------------\n");
    }

})();