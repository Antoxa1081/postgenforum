<?

namespace common;

/**
 * Fields that define the language. 
 */
class Labels {

    const LANG_OPT = [
        "LABEL_INFO_INDEX",
        "LABEL_INFO_GROUPS",
        "LABEL_INFO_THEMES",
        "LABEL_NOTOPICS",
        "LABEL_BACKTOTHEME",
        "LABEL_BACKTOGROUP",
        "LABEL_CREATE_NEWGROUP",
        "LABEL_CREATE_NEWTHEME",
        "LABEL_CREATE_NEWTHREAD",
        "LABEL_WRITENOTE",
    ];

    /*
     * English version
     * (Default)
     */
    const LABELS_EN = ([
        LABEL_INFO_INDEX => "Theme is the largest unit of the forum. Inside the theme are creating threads. Only moderators and administrators can create themes.",
        LABEL_INFO_GROUPS => "Users can join groups. Also, if you are in a group, you can create threads for it by writing \"[b] the name of the group [/b]@ name of thread \". Also, you can create your own group.",
        LABEL_NOTOPICS => "There are no topics",
        LABEL_BACKTOTHEME => "Back to theme",
        LABEL_BACKTOGROUP => "Back to group",
        LABEL_CREATE_NEWGROUP => "Create a new group",
        LABEL_CREATE_NEWTHEME => "New theme",
        LABEL_CREATE_NEWTHREAD => "New thread",
        LABEL_WRITENOTE => "Write",
            //    LABEL_SUCCESSFULL_ACCOUNT_UPDATE => "Your account has been successfully updated.",
            //    LABEL_SUCCESSFULL_ACCOUNT_UPDATE => "Your account has been successfully updated.",
            //    LABEL_SUCCESSFULL_ACCOUNT_UPDATE => "Your account has been successfully updated.",
            //    LABEL_SUCCESSFULL_ACCOUNT_UPDATE => "Your account has been successfully updated."
    ]);
    public static $Lang =  self::LABELS_EN;

    /**
     * Returns true if all that define the language isset
     * @param type $L
     * @return boolean
     */
    public static function validLangpack($L) {
        foreach (LANG_OPT as $g) {
            if (!isset($L, $g))
                return false;
        }
        return true;
    }

}

?>
