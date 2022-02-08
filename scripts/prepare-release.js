import yargs from "yargs";
import inquirer from 'inquirer';
import { updateVersion, updateEnvironmentManifest } from './util/update-files.js';
import { createTaggedRelease, getVersionTag, getVersion } from './util/tag-files.js';
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
} from "./util/git.js";

const argv = yargs(process.argv.slice(2)).argv;

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