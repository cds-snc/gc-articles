import rp from 'request-promise';
import * as cheerio from 'cheerio';
import inquirer from 'inquirer';

const millisToMinutesAndSeconds = (ms) => {
    let d = new Date(1000 * Math.round(ms / 1000)); // round to nearest second
    const pad = (i) => { return ('0' + i).slice(-2); }
    return pad(d.getUTCMinutes()) + ':' + pad(d.getUTCSeconds());
}

const scrapeHtml = async (timer = 0) => {
    const url = 'https://articles.cdssandbox.xyz';

    return new Promise(async (resolve, reject) => {
        try {

            const requestUrl = `${url}?v=${encodeURI(timer).replace(':', '')}`

            const html = await rp(requestUrl, {
                headers: {
                    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36'
                },
            });
            const $ = cheerio.load(html);
            const version = $('#version').text();
            console.log(`${timer} - ${requestUrl} => found version: ${version}`);
            resolve(version)
        } catch (e) {
            reject(e)
        }
    })
}

const versionMatch = (currentVersion, version) => {
    if (currentVersion == version) {
        console.log("found match");
        return true;
    }
    return false;
}

const checkForVersion = async (version) => {
    const checkInterval = 10000; // time between checks (10 seconds)
    const clearTimer = () => {
        console.log("stop checks")
        clearInterval(timer);
    }

    const check = async () => {
        try {
            const humanTime = millisToMinutesAndSeconds(elasped);
            if (versionMatch(version, await scrapeHtml(humanTime))) {
                clearTimer();
            }
        } catch (e) {
            console.log(e)
        }
    }

    let elasped = 0;
    let timer = setInterval(async () => {
        elasped += checkInterval;
        check();
    }, checkInterval);

    check();
}

const inputVersionNumber = async () => {
    const question = {
        type: 'input',
        name: 'version',
        message: "Check for version (i.e. 1.0.0):",

    }
    const answer = await inquirer.prompt(question);
    return answer.version;
}

try {
    const version = await inputVersionNumber();
    console.log(`start checking for version: ${version}`)
    checkForVersion(version);
} catch (e) {
    console.log(e.message)
}
