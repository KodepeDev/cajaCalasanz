<footer class="footer">
    <table cellpadding="0" celspacing="0" width="100%">
        <tr>
            <th width="20%">
                <span>v 1.0</span>
            </th>
            <th width="60%" class="text-center">
                {{ Auth::user()->first_name }}
            </th>
            <th width="20%">
                <span class="pagenum"></span>
            </th>
        </tr>
    </table>
</footer>
