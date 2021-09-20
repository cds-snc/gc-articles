import yargs from "yargs";
import inquirer from 'inquirer';
import { updateVersion, updateTerragruntHcl } from './util/update-files.js';
import { createTaggedRelease } from './util/tag-files.js';
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
    ghReleasePullRequest
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
    const questions = [
        {
            type: 'input',
            name: 'tag',
            message: "Release tag (v1.x.x)",
        },
        {
            type: 'input',
            name: 'notes',
            message: "Release notes i.e. bug fixes",
        }
    ];

    const answer = await inquirer.prompt(questions);
    return { tag: answer.tag, notes: answer.notes };
}

(async () => {
    try {
        if (argv.version_num) {
            const version = await inputVersionNumber();
            await gitCreateVersionBranch(version);
            await updateVersion(version);
            await gitAddVersionFiles();
            await gitCommitVersionFiles(version);
            await gitPushVersionFiles(version);
            await ghVersionPullRequest(version);
        }

        if (argv.tag) {
            const { tag, notes } = await inputReleaseTag();
            await gitCreateReleaseBranch(tag);
            await updateTerragruntHcl(tag);
            await gitAddReleaseFiles();
            await gitCommitReleaseFiles(tag);
            await gitPushReleaseFiles(tag);
            await ghReleasePullRequest(tag, notes);
            createTaggedRelease(tag, notes);
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