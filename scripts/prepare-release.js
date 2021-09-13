import inquirer from 'inquirer';
import { updateVersion, updateTerragruntHcl } from './util/update-files.js';
import { createTaggedRelease } from './util/tag-files.js';

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
    ]


    const answer = await inquirer.prompt(questions);
    return { tag: answer.tag, notes: answer.notes };
}

(async () => {
    try {
        const answer = await inquirer.prompt([
            {
                type: 'list',
                name: 'action',
                message: 'What do you want to do?',
                choices: [
                    'Bump version (1.x.x)',
                    'Update docker image (v1.x.x)',
                ],
            }
        ]);

        if (answer.action === 'Bump version (1.x.x)') {
            await updateVersion(await inputVersionNumber())
        } else {
            const { tag, notes } = await inputReleaseTag()
            await updateTerragruntHcl(tag);
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