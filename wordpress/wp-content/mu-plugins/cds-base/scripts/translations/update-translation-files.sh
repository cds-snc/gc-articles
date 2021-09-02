HERE=`dirname $0`
ROOT="$HERE/../.."
for file in `find "$ROOT/languages" -name "*.po"`
do
  msgmerge -U $file "$ROOT/languages/cds-snc.pot"
done
