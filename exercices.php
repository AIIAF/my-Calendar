<!DOCTYPE html>
<html>
    <head>
        <title>Liste bornée</title>
        <meta charset="utf-8">
        <script>
//            var list = document.getElementById("list");
//            var inf = document.getElementById("inf");
//            var sup = document.getElementById("sup"); 
            var list, inf, sup;

            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    list = document.getElementById("list");
                    inf = document.getElementById("inf");
                    sup = document.getElementById("sup");
                }
            };
            function generateList() {
                var min = parseInt(inf.value);
                var max = parseInt(sup.value);
                var html = "<ul>";
                for (var i = min; i <= max; i++) {
                    html += "<li>" + i + "</li>";
                }
                html += "</ul>";
                list.innerHTML = html;
            }
        </script>
    </head>
    <body>
        <table>
            <tr>
                <td>Borne inférieure :</td>
                <td><input id="inf" type="number" value="1"><br></td>
            </tr>
            <tr>
                <td>Borne supérieure :</td>
                <td><input id="sup" type="number" value="20"><br></td>
            </tr>
            <tr>
                <td colspan="2"><input id="btn" type="button" value="Générer liste" onclick="generateList();"></td>
            </tr>
        </table>
        <p id="list"></p>
    </body>
</html>