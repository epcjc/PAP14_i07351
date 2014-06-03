Imports System
Imports System.Net
Imports System.IO
Imports System.Text


Public Class Class1

    Public Sub download_ftp(ByVal caminhoficheiro As String)

        Dim request As FtpWebRequest = WebRequest.Create("ftp://projetos.epcjc.net/" & caminhoficheiro)
        request.Method = WebRequestMethods.Ftp.DownloadFile

        request.Credentials = New NetworkCredential("i07351", "trabfef5")
        Dim response As FtpWebResponse = request.GetResponse()

        Dim responseStream As Stream = response.GetResponseStream()

        Dim reader As StreamReader = New StreamReader(responseStream)

        Console.WriteLine(reader.ReadToEnd())
        Console.WriteLine("Download Complete, status {0}", response.StatusDescription)

        reader.Close()
        response.Close()

    End Sub

    Public Sub upload_ftp(ByVal ficheiro As String, caminho As String)

        Dim request As FtpWebRequest = WebRequest.Create("ftp://projetos.epcjc.net/" & caminho)
        request.Method = WebRequestMethods.Ftp.UploadFile

        request.Credentials = New NetworkCredential("i07351", "trabfef5")
        Dim sourcestream As StreamReader = New StreamReader(ficheiro)

        Dim fileContents = Encoding.UTF8.GetBytes(sourcestream.ReadToEnd())
        sourcestream.Close()
        request.ContentLength = fileContents.Length

        Dim requestStream As Stream = request.GetRequestStream()
        requestStream.Write(fileContents, 0, fileContents.Length)
        requestStream.Close()

        Dim response As FtpWebResponse = request.GetResponse()

        Console.WriteLine("Upload File Complete, status {0}", response.StatusDescription)

        response.Close()

    End Sub


End Class
