Set objArgs = WScript.Arguments
oibcdo = ""
For x = 0 To objArgs.Count - 1
oibcdo = oibcdo & " " & objArgs(x)
Next
Set ws = CreateObject("Wscript.Shell")
ws.run "cmd /c " & oibcdo