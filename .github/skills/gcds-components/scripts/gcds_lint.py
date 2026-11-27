#!/usr/bin/env python3
"""Lint GC Design System (GCDS) component usage against custom-elements.json.

Checks every <gcds-*> tag (HTML, Vue, Angular, Svelte, templates) and <Gcds*> JSX element:
  * unknown component tags
  * unknown attributes / props (kebab-case, camelCase, :bound, [bound] all understood)
  * invalid values for enumerated attributes
  * missing required attributes
  * malformed JSON in object/array attributes (options, columns, contextual-links, ...)
  * unknown gcds* event names (onGcdsX, @gcdsX, (gcdsX), addEventListener('gcdsX'))
  * self-closing custom elements in plain HTML (browsers do not support <gcds-x />)

--inventory lists native / WET / GCWeb / Bootstrap markup that has a GCDS equivalent, for conversions.

Usage:
  python3 gcds_lint.py <file-or-dir> [...]           # lint
  python3 gcds_lint.py --inventory <file-or-dir> ...  # find conversion candidates
  python3 gcds_lint.py --api gcds-input [gcds-select ...]  # print a component's API summary
  python3 gcds_lint.py --schema path/to/custom-elements.json ...
Markdown files are supported: fenced code blocks are linted.
Exit status: 1 if any error was reported, else 0.
"""
from __future__ import annotations

import argparse
import html
import json
import re
import sys
from dataclasses import dataclass
from pathlib import Path

DEFAULT_SCHEMA = Path(__file__).resolve().parent.parent / "references" / "custom-elements.json"
EXTENSIONS = {
    ".html", ".htm", ".jsx", ".tsx", ".js", ".ts", ".mjs", ".vue", ".svelte", ".astro", ".njk", ".liquid",
    ".hbs", ".handlebars", ".ejs", ".erb", ".php", ".cshtml", ".razor", ".jsp", ".twig", ".j2", ".jinja", ".md",
}
PLAIN_HTML = {".html", ".htm", ".md", ".njk", ".liquid", ".hbs", ".handlebars", ".ejs", ".erb", ".php", ".twig",
              ".j2", ".jinja", ".jsp"}
SKIP_DIRS = {"node_modules", ".git", "dist", "build", ".next", ".nuxt", "coverage", ".cache", "vendor", "www"}

GLOBAL_ATTRS = {
    "id", "class", "classname", "style", "slot", "lang", "dir", "hidden", "title", "tabindex", "role", "key", "ref",
    "part", "is", "inert", "translate", "nonce", "accesskey", "contenteditable", "draggable", "spellcheck",
    "enterkeyhint", "autocapitalize", "children", "dangerouslysetinnerhtml", "suppresshydrationwarning", "htmlfor",
    # framework bindings that are not component props
    "formcontrolname", "formcontrol", "routerlink", "queryparams", "fragment", "gcdsrouterlink", "gcdscell",
}
TEMPLATE_SYNTAX = re.compile(r"\{\{|\$\{|<%|\{%|@\(|@[A-Za-z]")


@dataclass
class Issue:
    path: str
    line: int
    level: str
    msg: str

    def __str__(self) -> str:
        return f"{self.path}:{self.line}: {self.level}: {self.msg}"


def kebab(name: str) -> str:
    return re.sub(r"(?<!^)(?=[A-Z])", "-", name).lower()


def load_schema(path: Path) -> dict[str, dict]:
    data = json.loads(path.read_text())
    out = {}
    for m in data["modules"]:
        for d in m.get("declarations", []):
            if not d.get("tagName"):
                continue
            attrs = {}
            for a in d.get("attributes", []):
                t = a.get("type", {}).get("text", "")
                parts = [p.strip() for p in t.split("|")]
                literals = [p.strip('"') for p in parts if re.fullmatch(r'"[^"]*"|-?\d+', p)]
                attrs[a["name"]] = {
                    "type": t,
                    "enum": set(literals) if literals and len(literals) == len(parts) else None,
                    "required": (a.get("description") or "").startswith("(required)"),
                    "json": bool(re.search(r"object|\[\]", t)),
                    "boolean": t.strip() == "boolean",
                }
            out[d["tagName"]] = {
                "attrs": attrs,
                "props": {kebab(mm["name"]) for mm in d.get("members", []) if mm.get("kind") == "field"},
                "events": {e["name"] for e in d.get("events", [])},
            }
    return out


# --------------------------------------------------------------------------- tag scanning

TAG_START = re.compile(r"<(gcds-[a-z0-9-]+|Gcds[A-Za-z0-9]+)(?=[\s/>])")
# Framework-specific wrapper tags that share a web component's API.
TAG_ALIASES = {"gcds-table-ng": "gcds-table", "gcds-table-with-slots": "gcds-table"}


def scan_tag(text: str, start: int) -> str:
    """Return the raw attribute text of the tag beginning at `start`, honouring quotes and JSX braces."""
    i, depth, quote = start, 0, None
    while i < len(text):
        c = text[i]
        if quote:
            if c == quote:
                quote = None
        elif c in "\"'`":
            quote = c
        elif c == "{":
            depth += 1
        elif c == "}":
            depth -= 1
        elif c == ">" and depth <= 0:
            return text[start:i]
        i += 1
    return text[start:]


ATTR_NAME_END = set(" \t\r\n=>/")


def read_balanced(raw: str, i: int) -> int:
    """`raw[i]` is '{'; return the index just past its matching '}' (quote and template-literal aware)."""
    depth, quote = 0, None
    while i < len(raw):
        c = raw[i]
        if quote:
            if c == "\\":
                i += 1
            elif c == quote:
                quote = None
        elif c in "\"'`":
            quote = c
        elif c == "{":
            depth += 1
        elif c == "}":
            depth -= 1
            if depth == 0:
                return i + 1
        i += 1
    return len(raw)


def parse_attrs(raw: str) -> list[tuple[str, str | None, bool]]:
    """-> [(name, value-or-None, is_static_value)]"""
    out, i, n = [], 0, len(raw)
    while i < n:
        while i < n and raw[i] in " \t\r\n/":
            i += 1
        if i >= n:
            break
        if raw[i] == "{":
            end = read_balanced(raw, i)
            out.append(("{..." + raw[i + 1:end - 1].strip().lstrip("."), None, False))
            i = end
            continue
        start = i
        while i < n and raw[i] not in ATTR_NAME_END:
            i += 1
        name = raw[start:i]
        if not name:
            i += 1
            continue
        j = i
        while j < n and raw[j] in " \t\r\n":
            j += 1
        if j >= n or raw[j] != "=":
            out.append((name, None, True))
            continue
        j += 1
        while j < n and raw[j] in " \t\r\n":
            j += 1
        if j < n and raw[j] in "\"'":
            end = raw.find(raw[j], j + 1)
            end = n if end < 0 else end
            out.append((name, raw[j + 1:end], True))
            i = end + 1
        elif j < n and raw[j] == "{":
            end = read_balanced(raw, j)
            inner = raw[j + 1:end - 1].strip()
            lit = re.fullmatch(r"""(["'`])(.*)\1""", inner, re.S)
            if lit and "${" not in inner:
                out.append((name, lit.group(2), True))
            elif re.fullmatch(r"true|false|-?\d+(\.\d+)?", inner):
                out.append((name, inner, True))
            else:
                out.append((name, inner, False))
            i = end
        else:
            end = j
            while end < n and raw[end] not in " \t\r\n>":
                end += 1
            out.append((name, raw[j:end], True))
            i = end
    return out


def normalize(name: str, jsx: bool) -> tuple[str, str, bool]:
    """-> (kind, key, dynamic); kind is one of attr, event, spread, skip."""
    def prop(n: str) -> str:
        return n if "-" in n else kebab(n)

    if name.startswith("{...") or name == "v-bind":
        return "spread", "", True
    if name.startswith(("v-bind:", ":")):
        return "attr", prop(name.split(":", 1)[1]), True
    if name.startswith(("v-on:", "@")):
        return "event", name.split(":", 1)[1] if name.startswith("v-on:") else name[1:], True
    if name.startswith("on:"):
        return "event", name[3:], True
    if name.startswith("bind:"):
        return "attr", prop(name[5:]), True
    if name.startswith(("[(", "*", "#", "v-", "x-", "hx-", "ng", "(click", "[attr.", "[class", "[style")):
        return "skip", "", True
    if name.startswith("[") and name.endswith("]"):
        return "attr", prop(name[1:-1]), True
    if name.startswith("(") and name.endswith(")"):
        return "event", name[1:-1], True
    if jsx and name in ("className", "htmlFor"):
        return "skip", "", False
    if jsx and re.match(r"on[A-Z]", name):
        return "event", name[2:], False
    if jsx and re.search(r"[A-Z]", name):
        return "attr", kebab(name), False
    return "attr", name.lower(), False


def event_name(raw: str) -> str:
    """gcds-change / gcdsChange / GcdsChange -> gcdsChange"""
    if "-" in raw:
        head, *rest = raw.split("-")
        raw = head + "".join(p.title() for p in rest)
    return raw[:1].lower() + raw[1:]


def lint_text(text: str, path: str, schema: dict, line_offset: int = 0, plain_html: bool = False) -> list[Issue]:
    issues: list[Issue] = []
    # Angular binds camelCase static attributes to the wrapper's @Input()s, like JSX props.
    angular = path.endswith(".component.html") or bool(
        re.search(r"\*ng[A-Z]|\[\(ngModel\)\]|\((gcds[A-Z]\w*)\)=|@Component\(", text))

    def line_of(pos: int) -> int:
        return text.count("\n", 0, pos) + 1 + line_offset

    for m in TAG_START.finditer(text):
        raw_tag = m.group(1)
        jsx = raw_tag.startswith("Gcds")
        camel = jsx or angular
        tag = kebab(raw_tag) if jsx else raw_tag
        tag = TAG_ALIASES.get(tag, tag)
        line = line_of(m.start())
        raw_attrs = scan_tag(text, m.end())
        if tag not in schema:
            issues.append(Issue(path, line, "error", f"unknown component <{raw_tag}>"))
            continue
        spec = schema[tag]
        if plain_html and raw_attrs.rstrip().endswith("/"):
            issues.append(Issue(path, line, "error",
                                f"<{tag} /> is self-closing; custom elements need an explicit </{tag}> in HTML"))
        seen: set[str] = set()
        has_spread = False
        for name, val, static in parse_attrs(raw_attrs.rstrip().rstrip("/")):
            kind, key, dynamic = normalize(name, camel)
            if kind == "spread":
                has_spread = True
                continue
            if kind == "skip":
                continue
            if kind == "event":
                ev = event_name(key)
                if ev.startswith("gcds") and ev not in spec["events"]:
                    known = ", ".join(sorted(spec["events"])) or "none"
                    issues.append(Issue(path, line, "error", f"<{tag}> has no event '{ev}' (events: {known})"))
                continue
            if key.replace("-", "") in GLOBAL_ATTRS or key.startswith(("data-", "aria-")) or re.fullmatch(r"on[a-z]+", key):
                continue
            seen.add(key)
            attr = spec["attrs"].get(key)
            if attr is None:
                if key in spec["props"]:
                    if not dynamic and not jsx:
                        issues.append(Issue(path, line, "warning", f"<{tag}> '{key}' is a JS-only property; "
                                                                   "set it from JavaScript, not as an HTML attribute"))
                    continue
                close = [a for a in spec["attrs"] if a.replace("-", "") == key.replace("-", "")]
                hint = f" (did you mean '{close[0]}'?)" if close else ""
                issues.append(Issue(path, line, "error", f"<{tag}> has no attribute '{key}'{hint}"))
                continue
            if val is None or not static or dynamic:
                continue
            v = html.unescape(val).strip()
            if TEMPLATE_SYNTAX.search(v):
                continue
            if attr["enum"] is not None and v not in attr["enum"]:
                issues.append(Issue(path, line, "error",
                                    f"<{tag} {key}=\"{v}\"> must be one of: {', '.join(sorted(attr['enum']))}"))
            if attr["boolean"] and v not in ("", "true", "false", key):
                issues.append(Issue(path, line, "warning", f"<{tag} {key}=\"{v}\"> should be true/false"))
            if attr["json"] and v[:1] in "[{":
                try:
                    json.loads(v)
                except json.JSONDecodeError as e:
                    issues.append(Issue(path, line, "error", f"<{tag} {key}> is not valid JSON: {e.msg}"))
        if not has_spread:
            missing = [a for a, s in spec["attrs"].items() if s["required"] and a not in seen]
            if missing:
                issues.append(Issue(path, line, "error",
                                    f"<{tag}> is missing required attribute(s): {', '.join(missing)}"))

    all_events = set().union(*(s["events"] for s in schema.values()))
    for m in re.finditer(r"addEventListener\(\s*['\"](gcds[A-Za-z]+)['\"]", text):
        if m.group(1) not in all_events:
            issues.append(Issue(path, line_of(m.start()), "error", f"no GCDS component emits '{m.group(1)}'"))
    issues += check_parents(text, path, line_of)
    if plain_html:
        issues += check_document(text, path, line_of)
    return issues


def check_document(text: str, path: str, line_of) -> list[Issue]:
    """Page-level checks, only for complete HTML documents (files containing <html)."""
    issues: list[Issue] = []
    for m in re.finditer(r"<gcds-table\b", text):
        end = text.find("</gcds-table>", m.end())
        if end > 0 and 'slot="caption"' not in text[m.end():end]:
            issues.append(Issue(path, line_of(m.start()), "warning",
                                '<gcds-table> has no <... slot="caption"> - tables need an accessible name'))
    html_tag = re.search(r"<html\b[^>]*>", text, re.I)
    if not html_tag:
        return issues
    if not re.search(r"""\blang=["']?(en\b|fr\b|\{\{|\{%|<%|<\?|@|\$\{)""", html_tag.group(0)):
        issues.append(Issue(path, line_of(html_tag.start()), "error", '<html> needs lang="en" or lang="fr"'))
    if not re.search(r"<title>\s*\S", text, re.I):
        issues.append(Issue(path, 1, "error", "missing or empty <title>"))
    body = re.sub(r"<!--.*?-->", "", text, flags=re.S)
    h1s = len(re.findall(r"<h1\b|<(?:gcds-heading|gcds-stepper|gcds-sr-only)\b[^>]*\btag=[\"']h1[\"']", body))
    if h1s != 1:
        issues.append(Issue(path, 1, "warning", f"page has {h1s} h1 headings; expected exactly 1"))
    header = re.search(r"<gcds-header\b[^>]*>", body)
    if not header:
        issues.append(Issue(path, 1, "warning", "page has no <gcds-header>"))
    else:
        skip = re.search(r"""skip-to-href=["']#([^"']+)["']""", header.group(0))
        if skip and not re.search(rf"""\bid=["']{re.escape(skip.group(1))}["']""", body):
            issues.append(Issue(path, line_of(header.start()), "error",
                                f"skip-to-href target #{skip.group(1)} does not exist on the page"))
    if "<gcds-footer" not in body:
        issues.append(Issue(path, 1, "warning", "page has no <gcds-footer>"))
    if "<gcds-date-modified" not in body:
        issues.append(Issue(path, 1, "warning", "page has no <gcds-date-modified> (required on Canada.ca)"))
    return issues


# Components that only work as direct children of specific parents (they inspect parentNode).
REQUIRED_PARENTS = {
    "gcds-nav-link": {"gcds-top-nav", "gcds-side-nav", "gcds-nav-group"},
    "gcds-nav-group": {"gcds-top-nav", "gcds-side-nav", "gcds-nav-group"},
    "gcds-breadcrumbs-item": {"gcds-breadcrumbs"},
    "gcds-grid-col": {"gcds-grid"},
}
VOID = {"area", "base", "br", "col", "embed", "hr", "img", "input", "link", "meta", "source", "track", "wbr"}
TRANSPARENT = {"template", "slot", "ng-container", "ng-template", "Fragment", "React.Fragment", ""}


def check_parents(text: str, path: str, line_of) -> list[Issue]:
    """Flag nav / breadcrumb / grid-col children whose direct parent element is wrong."""
    issues: list[Issue] = []
    stack: list[str] = []
    clean = re.sub(r"<!--.*?-->", lambda m: " " * len(m.group(0)), text, flags=re.S)
    for m in re.finditer(r"<(/?)([A-Za-z][\w.:-]*)?", clean):
        closing, name = m.group(1), m.group(2) or ""
        if not closing and not name and clean[m.end():m.end() + 1] != ">":
            continue  # a bare '<' (comparison operator etc.)
        raw = scan_tag(clean, m.end())
        tag = kebab(name) if name.startswith("Gcds") else name
        tag = TAG_ALIASES.get(tag, tag)
        if closing:
            if tag in stack:
                while stack and stack.pop() != tag:
                    pass
            continue
        if tag in REQUIRED_PARENTS:
            parent = next((p for p in reversed(stack) if p not in TRANSPARENT), None)
            # Unknown framework components (e.g. <AppLink>) may render the right element; don't guess.
            if parent and parent[:1].islower() and parent not in REQUIRED_PARENTS[tag]:
                issues.append(Issue(path, line_of(m.start()), "error",
                                    f"<{tag}> must be a direct child of {' / '.join(sorted(REQUIRED_PARENTS[tag]))}, "
                                    f"not <{parent}>"))
        if raw.rstrip().endswith("/") or tag.lower() in VOID:
            continue
        stack.append(tag)
    return issues


# --------------------------------------------------------------------------- inventory

CLS = r"class(?:Name)?=[\"'][^\"']*"
INVENTORY = [
    (rf"<header\b|id=[\"']wb-bnr|{CLS}\b(gc-header|navbar)\b", "site header",
     "<gcds-header lang-href skip-to-href> (+ search / breadcrumb / menu slots)"),
    (r"<footer\b|id=[\"']wb-info", "site footer", "<gcds-footer>"),
    (rf"id=[\"']wb-lng|{CLS}\blang-toggle", "language toggle", "<gcds-header lang-href> (or <gcds-lang-toggle>)"),
    (r"id=[\"']wb-srch|<input[^>]*type=[\"']search", "search box", "<gcds-search slot=\"search\">"),
    (rf"id=[\"']wb-bc|{CLS}\bbreadcrumbs?\b", "breadcrumbs",
     "<gcds-breadcrumbs slot=\"breadcrumb\"> + <gcds-breadcrumbs-item>"),
    (rf"{CLS}\bgcweb-menu|id=[\"']wb-sm", "Canada.ca theme menu", "<gcds-topic-menu slot=\"menu\">"),
    (rf"id=[\"']wb-sec|{CLS}\b(sidebar|side-nav|nav-stacked|list-group)\b", "side navigation",
     "<gcds-side-nav> + <gcds-nav-group> / <gcds-nav-link>"),
    (r"<nav\b", "nav landmark", "<gcds-top-nav>, <gcds-side-nav>, <gcds-breadcrumbs> or <gcds-pagination>"),
    (rf"id=[\"']wb-dtmd|{CLS}\bpagedetails", "date modified", "<gcds-date-modified>"),
    (r"<main\b", "main landmark", "<gcds-container id=\"main-content\" layout=\"page\" tag=\"main\">"),
    (rf"{CLS}\bcontainer(-fluid)?\b", "layout container", "<gcds-container> or CSS Shortcuts container-*"),
    (rf"{CLS}\b(row|col(-(xs|sm|md|lg|xl))?-\d+|columns)\b", "grid / columns",
     "<gcds-grid columns-tablet columns-desktop> (+ <gcds-grid-col>)"),
    (r"<h[1-6]\b", "heading", "<gcds-heading tag=\"hN\">"),
    (r"<p\b", "paragraph", "<gcds-text>"),
    (rf"<a\b[^>]*{CLS}\bbtn\b", "link styled as button", "<gcds-button type=\"link\" href>"),
    (r"<a\b", "link", "<gcds-link href> (or <gcds-nav-link> / <gcds-breadcrumbs-item> inside navs)"),
    (r"<button\b|<input[^>]*type=[\"'](submit|button|reset)", "button", "<gcds-button type button-role>"),
    (r"<input[^>]*type=[\"']checkbox", "checkbox", "<gcds-checkboxes name legend options>"),
    (r"<input[^>]*type=[\"']radio", "radio", "<gcds-radios name legend options>"),
    (r"<input[^>]*type=[\"']file", "file input", "<gcds-file-uploader uploader-id name label>"),
    (r"<input[^>]*type=[\"'](date|month)", "date input", "<gcds-date-input name legend format>"),
    (r"<input\b(?![^>]*type=[\"'](checkbox|radio|file|date|month|submit|button|reset|hidden|search|image))",
     "text input", "<gcds-input input-id name label>"),
    (r"<select\b", "select", "<gcds-select select-id name label>"),
    (r"<textarea\b", "textarea", "<gcds-textarea textarea-id name label>"),
    (r"<fieldset\b", "fieldset",
     "<gcds-fieldset legend legend-size> (gcds-radios/checkboxes/date-input already render a fieldset)"),
    (rf"{CLS}\b(help-block|form-text|hint)\b", "field hint", "hint=\"...\" on the GCDS form component"),
    (rf"{CLS}\b(invalid-feedback|has-error|error-message|label-danger)\b", "field error",
     "error-message=\"...\" / built-in validation"),
    (rf"{CLS}\berror-summary\b|id=[\"']errors?\b", "error summary", "<gcds-error-summary>"),
    (r"wb-frmvld|jquery\.validate|parsley(\.min)?\.js|data-parsley-", "form validation plugin",
     "GCDS built-in validation (required/type/pattern/validator) + <gcds-error-summary>; remove the plugin"),
    (rf"{CLS}\balert\b", "alert / notice box", "<gcds-notice> (in content) or <gcds-alert> (site-wide, event-driven)"),
    (rf"<details\b|{CLS}\b(accordion|collapse)\b", "expand / collapse", "<gcds-details details-title>"),
    (r"<table\b", "table", "<gcds-table columns data> (+ caption slot)"),
    (rf"{CLS}\b(card|panel|well|tile)\b", "card / panel", "<gcds-card card-title href> (or <gcds-container border>)"),
    (rf"{CLS}\b(pagination|pager)\b", "pagination", "<gcds-pagination label>"),
    (rf"{CLS}\b(stepper|steps|progress-tracker|gc-subway)\b", "progress steps",
     "<gcds-stepper current-step total-steps>"),
    (rf"{CLS}\b(sr-only|visually-hidden|wb-inv)\b", "visually hidden text", "<gcds-sr-only>"),
    (rf"{CLS}\b(glyphicon|fa|fas|far|bi|material-icons)\b", "icon font", "<gcds-icon name> (limited icon set)"),
    (r"wet-boew|gcweb", "WET / GCWeb asset", "remove; load GCDS gcds.css + gcds.esm.js instead"),
    (r"bootstrap(\.min)?\.(css|js)|tailwind", "3rd-party CSS framework", "replace utility classes with CSS Shortcuts"),
]


def inventory(text: str, path: str, line_offset: int = 0) -> list[Issue]:
    issues = []
    masked = re.sub(r"<(gcds-[a-z-]+|Gcds[A-Za-z]+)[^>]*>", lambda m: " " * len(m.group(0)), text)
    for pat, what, suggestion in INVENTORY:
        for m in re.finditer(pat, masked, flags=re.I):
            line = text.count("\n", 0, m.start()) + 1 + line_offset
            snippet = m.group(0).replace("\n", " ")[:40]
            issues.append(Issue(path, line, "convert", f"{what}: `{snippet}` -> {suggestion}"))
    return sorted(issues, key=lambda i: i.line)


# --------------------------------------------------------------------------- driver

def iter_files(paths: list[str]):
    for p in map(Path, paths):
        if p.is_dir():
            for f in sorted(p.rglob("*")):
                if f.is_file() and f.suffix.lower() in EXTENSIONS and not (set(f.parts) & SKIP_DIRS):
                    yield f
        elif p.is_file():
            yield p
        else:
            print(f"gcds_lint: no such file: {p}", file=sys.stderr)


def code_blocks(md: str):
    for m in re.finditer(r"^```[^\n]*\n(.*?)^```", md, flags=re.S | re.M):
        yield m.group(1), md.count("\n", 0, m.start(1))


def main() -> int:
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("paths", nargs="*")
    ap.add_argument("--api", nargs="+", metavar="TAG", help="print the API summary for these tags and exit")
    ap.add_argument("--schema", type=Path, default=DEFAULT_SCHEMA)
    ap.add_argument("--inventory", action="store_true", help="list markup that should be converted to GCDS")
    ap.add_argument("--quiet", action="store_true", help="only print errors")
    args = ap.parse_args()

    if args.api:
        quick = (args.schema.parent / "api-quick.md").read_text()
        for tag in args.api:
            tag = tag if tag.startswith("gcds-") else "gcds-" + tag
            m = re.search(rf"^## `{re.escape(tag)}`\n(.*?)(?=^## |\Z)", quick, flags=re.S | re.M)
            print(f"{tag}\n{m.group(1).strip()}\n" if m else f"{tag}: unknown component\n")
        return 0
    if not args.paths:
        ap.error("give files/directories to lint, or --api TAG")
    schema = load_schema(args.schema)
    issues: list[Issue] = []
    files = 0
    for f in iter_files(args.paths):
        files += 1
        text = f.read_text(errors="replace")
        suffix = f.suffix.lower()
        chunks = list(code_blocks(text)) if suffix == ".md" else [(text, 0)]
        for chunk, offset in chunks:
            if args.inventory:
                issues += inventory(chunk, str(f), offset)
            else:
                plain = suffix in PLAIN_HTML and not re.search(r"<Gcds[A-Z]|className=|\bv-[a-z]+=|\[[a-zA-Z]+\]=", chunk)
                issues += lint_text(chunk, str(f), schema, offset, plain)

    for i in issues:
        if not (args.quiet and i.level != "error"):
            print(i)
    if args.inventory:
        print(f"\n{len(issues)} conversion candidate(s) in {files} file(s).")
        return 0
    errors = sum(i.level == "error" for i in issues)
    warnings = sum(i.level == "warning" for i in issues)
    print(f"\n{files} file(s) checked: {errors} error(s), {warnings} warning(s).")
    return 1 if errors else 0


if __name__ == "__main__":
    sys.exit(main())
