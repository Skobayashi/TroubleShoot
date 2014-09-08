<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Trouble Shoot</title>
    </head>
    <style type="text/css">
        form table tbody tr th {
            width: 140px;
        }
    </style>
    <body style="padding:10px;">
        <h1>Trouble Shoot</h1>
        <form action="/phplib/submit.php" enctype="multipart/form-data" method="post">
            <table>
                <tbody>
                    <tr>
                        <th><label for="path">Generate Path</label></th>
                        <td><input style="width:300px;" type="text" name="path" /></td>
                    </tr>
                    <tr>
                        <th><label for="data_path">Data Path</label></th>
                        <td><input style="width:300px;" type="text" name="data_path" value="/Users/suguru/Desktop/TST" /></td>
                    </tr>
                    <tr>
                        <th><label for="file">File</label></th>
                        <td><input type="file" name="file" /></td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td><input type="radio" id="connector" name="type" value="connector" checked><label for="connector">ConnectorLayout</label>
                        <input type="radio" id="code" name="type" value="code"><label for="code">故障コード</label></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="submit" />
        </form>

        <br />
        <hr />
        <div style="padding:5px;">
            <h2>Read Me</h2>

            <p>DataPathにはpdfが置いてある場所を指定する。</p>
            <p>fileにはブックIDなどを記載したcsvを指定する</p>
            <p>csvのカラムはひとつ増しではない注意。publiserクラスの_buildSearchInfoメソッド参照。</p>
        </div>
    </body>
</html>
