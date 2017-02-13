@verbatim
<script type="text/x-handlebars-template" id="edit_popover_template">
    <div id="edit_popover" class="ccm-menu ccm-ui" style="display: block; top: {{top}}px; left: {{left}}px; position: absolute; ">
        <div class="popover below">
            <div class="arrow"></div>

            <div class="inner">
                <div class="content">
                    <ul>
                        <li><a class="ccm-menu-icon ccm-icon-edit-menu" Xonclick="ccm_hideMenus()" dialog-title="" dialog-append-buttons="true" dialog-modal="false" dialog-on-close="ccm_blockWindowAfterClose()" dialog-width="550" dialog-height="420" data-editoraction="EDITOR--edit_button" data-bID="{{bid}}">Edit</a></li>

<!--                         <li><a class="ccm-menu-icon ccm-icon-clipboard-menu" id="menuAddToScrapbook1250-213" href="#" onclick="javascript:ccm_addToScrapbook(1,1250,'Content%20One%20%3A%20Layout%201%20%3A%20Cell%201');return false;">Copy to Clipboard</a></li> -->

<!--                         <li><a class="ccm-menu-icon ccm-icon-move-menu" id="menuArrange1250-213" href="javascript:ccm_arrangeInit()">Move</a></li> -->

                        <li><a class="ccm-menu-icon ccm-icon-delete-menu" data-editoraction="EDITOR--delete_block" data-bID="{{bid}}">Delete</a></li>

<!--                         <li><a class="ccm-menu-icon ccm-icon-design-menu" onclick="ccm_hideMenus()" dialog-modal="false" dialog-title="Set Block Styles" dialog-width="475" dialog-height="500" dialog-append-buttons="true" id="menuChangeCSS1250-213" href="/index.php/tools/required/edit_block_popup.php?cID=1&amp;bID=1250&amp;arHandle=Content%20One%20%3A%20Layout%201%20%3A%20Cell%201&amp;btask=block_css&amp;modal=true&amp;width=300&amp;height=100" title="Design">Design</a></li> -->

<!--                         <li><a class="ccm-menu-icon ccm-icon-custom-template-menu" onclick="ccm_hideMenus()" dialog-append-buttons="true" dialog-modal="false" dialog-title="Custom Template" dialog-width="300" dialog-height="275" id="menuChangeTemplate1250-213" href="/index.php/tools/required/edit_block_popup.php?cID=1&bID=1250&arHandle=Content%20One%20%3A%20Layout%201%20%3A%20Cell%201&btask=template&modal=true&width=300&height=275" title="Custom Template">Custom Template</a></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</script>
@endverbatim